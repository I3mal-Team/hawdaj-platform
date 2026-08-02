<?php

namespace App\Http\Controllers\Dashboard\Cars;

use App\Events\RealTimeMessage;
use App\Http\Controllers\Controller;
use App\Models\CarRequest;
use App\Notifications\CarRequestNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CarRequestDetailsController extends Controller
{
    /**
     * @param Request $request
     * @return View|void
     */
    public function searchWithCar(Request $request)
    {
        $title = __('dashboard.car_request_data');
        $plate = $request->plate;

        $carRequest = CarRequest::with(['host', 'site', 'department', 'driver', 'car'])
            ->whereHas('car', function ($q) use ($plate) {
                $q->where('plate_en', '=', $plate)
                    ->orWhere('plate_ar', '=', $plate);
            })->where('delivery_date', '=', date('Y-m-d'))->latest()->first();

        if ($carRequest) {
            return view('dashboard.cars._modal', compact('title', 'carRequest'));
        }
    }

    /**
     * @param $id
     * @return Application|Factory|View|void
     */
    public function getCarRequest($id)
    {
        $title = __('dashboard.car_request_data');

        $carData = CarRequest::with(['host', 'site', 'department', 'driver', 'car'])
            ->where('id', '=', $id)
            ->first();

        if ($carData) {
            return view('dashboard.guard.car_details', compact('title', 'carData'));
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function carAction(Request $request): RedirectResponse
    {
        $carRequest = CarRequest::whereId($request->car_request_id)
            ->with('host', 'driver', 'car')
            ->first();

        $success = false;

        if ($request->status_action == 'checkin' && is_null($carRequest->status_action)) {
            $success = true;

            $carRequest->update([
                'status_action' => $request->status_action,
                'checkin' => Carbon::now()->toDateTimeString(),
                'checkin_note' => $request->some_note
            ]);

            $host = $carRequest->host;

            $data = [
                'host' => $host,
                'carRequest' => $carRequest,
                'invitation_id' => $carRequest->id,
                'car_request_id' => $carRequest->id,
                'type' => 'notify_host',
                'driver_id' => $carRequest->driver->id,
                'driver_name' => $carRequest->driver->contact_person_name,
            ];

            $data['url'] = route('dashboard.tasks');

            $message = [
                'invitation_id' => $data['invitation_id'],
                'host_id' => $data['host']->id,
                'driver' => $data['driver_id'],
                'title' => 'New Car',
                'message' => "New Meeting With {$data['driver_name']}",
            ];

            event(new RealTimeMessage($host->id, $message));

            try {
                $host->notify(new CarRequestNotification($data));
            } catch (Exception $e) {
            }

        } elseif ($request->status_action == 'checkout' && !is_null($carRequest->status_action)) {
            $success = true;

            $carRequest->update([
                'status_action' => $request->status_action,
                'checkout' => Carbon::now()->toDateTimeString(),
                'checkout_note' => $request->some_note
            ]);

        } elseif ($request->status_action == 'rejected' && is_null($carRequest->status_action)) {

            $success = true;

            $carRequest->update([
                'status_action' => $request->status_action,
                'checkout' => Carbon::now()->toDateTimeString(),
                'refuse_note' => $request->some_note
            ]);
        }

        return $success
            ? redirect()->back()->with([
                'status' => 'success',
                'message' => "Driver #$carRequest->driver_id has been $request->status_action successfully"
            ])
            : redirect()->back()->with([
                'status' => 'error',
                'message' => "Can't take action on this driver"
            ]);
    }
}
