<div class="row mb-4">
    <div class="col-6">
        <h3 class="text-muted">{{ __('dashboard.healthCheck') }}</h3>
    </div>
    <div class="col-6 text-right">
        <a href="#" class="btn btn-primary">
            <i class="flaticon-plus"></i>
            {{ __('dashboard.question') }}
        </a>
    </div>
</div>

<div class="card">
    <div class="card-cody">
        <!-- table -->
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>{{ __('dashboard.questions') }}</th>
                    <th>{{ __('dashboard.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Have you recently traveled to an area with known local spread of COVID-19?</td>
                    <td class="text-center">
                        <a href="#" class="btn btn-danger">
                            <i class="flaticon2-rubbish-bin"></i>
                        </a>
                        <a href="#" class="btn btn-primary">
                            <i class="flaticon2-pen"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
