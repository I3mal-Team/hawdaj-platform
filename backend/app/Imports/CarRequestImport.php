<?php

namespace App\Imports;

use App\Models\Car;
use App\Models\CarRequest;
use App\Models\Department;
use App\Models\Driver;
use App\Models\Site;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CarRequestImport implements ToArray, WithHeadingRow
{
    /**
     * @param array $rows
     * @return void
     */
    public function array(array $rows)
    {
        foreach ($rows as $row) {
            $row = (object)$row;

            DB::beginTransaction();

            //Handle creation of driver
            $driver = Driver::create([
                'contact_person_name' => $row->driver_contact_person_name ?? '',
                'email' => $row->driver_email ?? '',
                'id_number' => $row->driver_id_number ?? '',
                'phone' => $row->driver_phone ?? '',
                'vehicle_details' => $row->driver_vehicle_details ?? '',
                'licence' => $row->driver_licence ?? '',
                'remarks' => $row->driver_remarks ?? ''
            ]);

            //Handle creation of Car
            $car = Car::create([
                'plate_ar' => $row->car_plate_ar ?? '',
                'plate_en' => $row->car_plate_en ?? '',
                'description' => $row->car_description ?? '',
                'licence' => $row->car_licence ?? '',
                'type' => $row->car_type ?? '',
                'status' => 'approved',
            ]);

            $site = Site::firstOrCreate(['name' => $row->site]);
            $department = Department::firstOrCreate(['name' => $row->department]);
            $host = User::where(['email' => $row->host_email])->first();

            auth()->user()->sites()->sync(
                array_unique(array_merge(auth()->user()->sites()->pluck('id')->toArray(), [$site->id]))
            );

            //Handle creation of CarRequest
            CarRequest::create([
                'requester_id' => auth()->id() ?? 1,
                'host_id' => $host->id ?? 1,
                'site_id' => $site->id ?? 1,
                'department_id' => $department->id ?? 1,
                'driver_id' => $driver->id,
                'car_id' => $car->id,
                'delivery_date' => $row->delivery_date,
                'delivery_from_time' => $row->delivery_from_time,
                'delivery_to_time' => $row->delivery_to_time,
                'remarks' => $row->remarks ?? '',
            ]);

            DB::commit();
        }
    }
}
