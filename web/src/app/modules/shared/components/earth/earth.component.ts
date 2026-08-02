import { Component, Inject, Input, NgZone, PLATFORM_ID } from '@angular/core';
import am4geodata_worldLow from "@amcharts/amcharts4-geodata/worldLow";
import am5themes_Animated from "@amcharts/amcharts5/themes/Animated";
import { CommonModule, isPlatformBrowser } from '@angular/common';
import { SkeletonComponent } from '../skeleton/skeleton.component';
import { IGlobalMapItem } from './../../../../interfaces/home';
import { keys } from '../../configs/localstorage-key';
import { TranslateModule } from '@ngx-translate/core';
import { ChangeDetectorRef } from '@angular/core';
import * as am5map from '@amcharts/amcharts5/map';
import * as am5 from '@amcharts/amcharts5';
import { PublicService } from '../../services/public.service';

@Component({
  standalone: true,
  imports: [CommonModule, TranslateModule, SkeletonComponent],
  selector: 'app-earth',
  templateUrl: './earth.component.html',
  styleUrls: ['./earth.component.scss']
})
export class EarthComponent {
  private chart?: am5map.MapChart;
  countries: any = [];
  isLoading: boolean = false;
  currentLang = this.publicService.getCurrentLanguage();

  @Input() items: IGlobalMapItem[] = [];
  countriesCounters: any[] = [];

  constructor(
    @Inject(PLATFORM_ID) private platformId: Object,
    private publicService: PublicService,
    private cdr: ChangeDetectorRef,
    private zone: NgZone
  ) { }

  ngOnInit(): void {
  }
  
  ngAfterViewInit(): void {
    if (isPlatformBrowser(this.platformId)) {
      this.getGlobalMapData();
    }
    // this.getData();
  }
  getData(arr?: any): void {
    let data: any = arr;
    // let data: any = this.currentLang == 'ar' ? earthAr : this.currentLang == 'ru' ? earthRu : this.currentLang == 'zh' ? earthZh : earthEn;
    this.zone.runOutsideAngular(() => {
      let root = am5.Root.new("chartdiv");
      root.setThemes([am5themes_Animated.new(root)]);
      let chart = root.container.children.push(am5map.MapChart.new(root, {}));

      let polygonSeries: any = chart.series.push(
        am5map.MapPolygonSeries.new(root, {
          geoJSON: am4geodata_worldLow,
          exclude: ["AQ"]
        })
      );

      polygonSeries.mapPolygons.template.setAll({
        tooltipText: "{name}:{count}",
        // tooltipText: "{localize_name}:{count}",
        toggleKey: "active",
        interactive: true,
        fill: am5.color("#ccc"),
        fillOpacity: 1,
        stroke: am5.color("#ccc"),
        strokeWidth: 1,
        strokeOpacity: 1,
        shadowColor: am5.color("#000"),
        shadowOpacity: 0.3,
        shadowBlur: 5,
        tooltipPosition: "fixed",            // position mode of the tooltip
        nonScalingStroke: true,              // disables stroke scaling
        draggable: false,
        // makes the polygon draggable
        // ... potentially more properties
      });

      let bubbleSeries = chart.series.push(
        am5map.MapPointSeries.new(root, {
          valueField: "value",
          calculateAggregates: true,
          polygonIdField: "id"
        })
      );
      let circleTemplate = am5.Template.new({});

      bubbleSeries.bullets.push(function (root, series, dataItem) {
        let container = am5.Container.new(root, {});
        let circle: any = container.children.push(
          am5.Circle.new(root, {
            radius: 20,
            fillOpacity: 0.7,
            fill: am5.color(0x8b008b),
            cursorOverStyle: "pointer",
            tooltipText: `{name}: [bold]{value}[/]`
          })
        );

        let countryLabel = container.children.push(
          am5.Label.new(root, {
            text: "{name}",
            paddingLeft: 5,
            populateText: true,
            fontWeight: "bold",
            fontSize: 13,
            centerY: am5.p50
          })
        );

        circle.on("radius", function (radius: any) {
          countryLabel.set("x", radius);
        })

        return am5.Bullet.new(root, {
          sprite: container,
          dynamic: true
        });
      });

      bubbleSeries.bullets.push(function (root, series, dataItem) {
        return am5.Bullet.new(root, {
          sprite: am5.Label.new(root, {
            text: "{value.formatNumber('#.')}",
            fill: am5.color(0xffffff),
            populateText: true,
            centerX: am5.p50,
            centerY: am5.p50,
            textAlign: "center"
          }),
          dynamic: true
        });
      });

      // minValue and maxValue must be set for the animations to work
      bubbleSeries.set("heatRules", [
        {
          target: circleTemplate,
          dataField: "value",
          min: 10,
          max: 50,
          minValue: 0,
          maxValue: 100,
          key: "radius"
        }
      ]);

      bubbleSeries.data.setAll(data);

      updateData();
      setInterval(function () {
        updateData();
      }, 2000)
      function updateData() {
        for (var i = 0; i < bubbleSeries?.dataItems?.length; i++) {
          bubbleSeries?.data?.setIndex(i, { value: Math.round(Math.random() * 100), id: data[i].id, name: data[i].name })
        }
      }
    });
  }
  getGlobalMapData(): void {
    if (this.items?.length > 0) {
      let arr: any = [];
      this.items ? this.items?.forEach((el: any) => {
        arr?.push({
          "id": el.country_code,
          "name": el?.name,
          "value": el?.count
        },)
      }) : '';
      this.getData(arr.sort((a, b) => b.count - a.count)?.slice(0, 8));
      this.countries = arr;
      const sortedCountries: any = this.items?.sort((a, b) => b.count - a.count);
      const topFourCountries: any = sortedCountries?.slice(0, 5);

      this.countriesCounters = topFourCountries;
      this.cdr.detectChanges();
      this.isLoading = false;
    }
    // this.isLoading = true;
    // this.homeService?.getGlobalMapData()?.subscribe(
    //   (res: any) => {
    //     if (res?.code == 200) {
    //       let arr: any = [];
    //       res?.data ? res?.data?.forEach((el: any) => {
    //         arr?.push({
    //           "id": el.country_code,
    //           "name": el?.name,
    //           "value": el?.count
    //         },)
    //       }) : '';
    //       this.getData(arr.sort((a, b) => b.count - a.count).slice(0, 8));
    //       this.countries = arr;
    //       const sortedCountries: any = res?.data.sort((a, b) => b.count - a.count);
    //       const topFourCountries = sortedCountries.slice(0, 5);

    //       this.countriesCounters = topFourCountries;
    //       this.isLoading = false;


    //     } else {
    //       this.isLoading = false;
    //       res?.message
    //         ? this.alertsService?.openToast('error', res?.message)
    //         : '';
    //     }
    //   },
    //   (err: any) => {
    //     err ? this.alertsService?.openToast('error', err?.message) : '';
    //     this.isLoading = false;
    //   }
    // );
  }

  ngOnDestroy(): void {
    if (this.chart) {
      this.chart.dispose();
    }
  }
}

