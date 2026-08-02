<!-- rate modal -->
<div class="modal fade" id="rating" tabindex="-1" aria-labelledby="rating" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <form class="modal-content rating" method="GET" action="/" id="rateForm">
            <div class="modal-header">
                <h5 class="modal-title">{{__("Rate")}}</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body py-3">
                <div class="form-group mb-0 d-flex justify-content-center">
                    <div class="rating__stars">

                        <input id="rating-1" class="rating__input rating__input-1" type="radio" name="rating"
                               value="1">
                        <input id="rating-2" class="rating__input rating__input-2" type="radio" name="rating"
                               value="2">
                        <input id="rating-3" class="rating__input rating__input-3" type="radio" name="rating"
                               value="3">
                        <input id="rating-4" class="rating__input rating__input-4" type="radio" name="rating"
                               value="4">
                        <input id="rating-5" class="rating__input rating__input-5" type="radio" name="rating"
                               value="5">

                        <label class="rating__label" for="rating-1">
                            <svg class="rating__star" width="32" height="32" viewBox="0 0 32 32"
                                 aria-hidden="true">
                                <g transform="translate(16,16)">
                                    <circle class="rating__star-ring" fill="none" stroke="#000" stroke-width="16"
                                            r="8" transform="scale(0)"/>
                                </g>
                                <g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <g transform="translate(16,16) rotate(180)">
                                        <polygon class="rating__star-stroke"
                                                 points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                                 fill="none"/>
                                        <polygon class="rating__star-fill"
                                                 points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                                 fill="#000"/>
                                    </g>
                                    <g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
                                        <polyline class="rating__star-line" transform="rotate(0)" points="0 4,0 16"/>
                                        <polyline class="rating__star-line" transform="rotate(72)" points="0 4,0 16"/>
                                        <polyline class="rating__star-line" transform="rotate(144)" points="0 4,0 16"/>
                                        <polyline class="rating__star-line" transform="rotate(216)" points="0 4,0 16"/>
                                        <polyline class="rating__star-line" transform="rotate(288)" points="0 4,0 16"/>
                                    </g>
                                </g>
                            </svg>
                            <span class="rating__sr">1 star—Terrible</span>
                        </label>
                        <label class="rating__label" for="rating-2">
                            <svg class="rating__star" width="32" height="32" viewBox="0 0 32 32"
                                 aria-hidden="true">
                                <g transform="translate(16,16)">
                                    <circle class="rating__star-ring" fill="none" stroke="#000" stroke-width="16"
                                            r="8" transform="scale(0)"/>
                                </g>
                                <g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <g transform="translate(16,16) rotate(180)">
                                        <polygon class="rating__star-stroke"
                                                 points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                                 fill="none"/>
                                        <polygon class="rating__star-fill"
                                                 points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                                 fill="#000"/>
                                    </g>
                                    <g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
                                        <polyline class="rating__star-line" transform="rotate(0)"
                                                  points="0 4,0 16"/>
                                        <polyline class="rating__star-line" transform="rotate(72)"
                                                  points="0 4,0 16"/>
                                        <polyline class="rating__star-line" transform="rotate(144)"
                                                  points="0 4,0 16"/>
                                        <polyline class="rating__star-line" transform="rotate(216)"
                                                  points="0 4,0 16"/>
                                        <polyline class="rating__star-line" transform="rotate(288)"
                                                  points="0 4,0 16"/>
                                    </g>
                                </g>
                            </svg>
                            <span class="rating__sr">2 stars—Bad</span>
                        </label>
                        <label class="rating__label" for="rating-3">
                            <svg class="rating__star" width="32" height="32" viewBox="0 0 32 32"
                                 aria-hidden="true">
                                <g transform="translate(16,16)">
                                    <circle class="rating__star-ring" fill="none" stroke="#000"
                                            stroke-width="16" r="8" transform="scale(0)"/>
                                </g>
                                <g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <g transform="translate(16,16) rotate(180)">
                                        <polygon class="rating__star-stroke"
                                                 points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                                 fill="none"/>
                                        <polygon class="rating__star-fill"
                                                 points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                                 fill="#000"/>
                                    </g>
                                    <g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
                                        <polyline class="rating__star-line" transform="rotate(0)"
                                                  points="0 4,0 16"/>
                                        <polyline class="rating__star-line" transform="rotate(72)"
                                                  points="0 4,0 16"/>
                                        <polyline class="rating__star-line" transform="rotate(144)"
                                                  points="0 4,0 16"/>
                                        <polyline class="rating__star-line" transform="rotate(216)"
                                                  points="0 4,0 16"/>
                                        <polyline class="rating__star-line" transform="rotate(288)"
                                                  points="0 4,0 16"/>
                                    </g>
                                </g>
                            </svg>
                            <span class="rating__sr">3 stars—OK</span>
                        </label>
                        <label class="rating__label" for="rating-4">
                            <svg class="rating__star" width="32" height="32" viewBox="0 0 32 32"
                                 aria-hidden="true">
                                <g transform="translate(16,16)">
                                    <circle class="rating__star-ring" fill="none" stroke="#000"
                                            stroke-width="16" r="8" transform="scale(0)"/>
                                </g>
                                <g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <g transform="translate(16,16) rotate(180)">
                                        <polygon class="rating__star-stroke"
                                                 points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                                 fill="none"/>
                                        <polygon class="rating__star-fill"
                                                 points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                                 fill="#000"/>
                                    </g>
                                    <g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
                                        <polyline class="rating__star-line" transform="rotate(0)"
                                                  points="0 4,0 16"/>
                                        <polyline class="rating__star-line" transform="rotate(72)"
                                                  points="0 4,0 16"/>
                                        <polyline class="rating__star-line" transform="rotate(144)"
                                                  points="0 4,0 16"/>
                                        <polyline class="rating__star-line" transform="rotate(216)"
                                                  points="0 4,0 16"/>
                                        <polyline class="rating__star-line" transform="rotate(288)"
                                                  points="0 4,0 16"/>
                                    </g>
                                </g>
                            </svg>
                            <span class="rating__sr">4 stars—Good</span>
                        </label>
                        <label class="rating__label" for="rating-5">
                            <svg class="rating__star" width="32" height="32" viewBox="0 0 32 32"
                                 aria-hidden="true">
                                <g transform="translate(16,16)">
                                    <circle class="rating__star-ring" fill="none" stroke="#000"
                                            stroke-width="16" r="8" transform="scale(0)"/>
                                </g>
                                <g stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <g transform="translate(16,16) rotate(180)">
                                        <polygon class="rating__star-stroke"
                                                 points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                                 fill="none"/>
                                        <polygon class="rating__star-fill"
                                                 points="0,15 4.41,6.07 14.27,4.64 7.13,-2.32 8.82,-12.14 0,-7.5 -8.82,-12.14 -7.13,-2.32 -14.27,4.64 -4.41,6.07"
                                                 fill="#000"/>
                                    </g>
                                    <g transform="translate(16,16)" stroke-dasharray="12 12" stroke-dashoffset="12">
                                        <polyline class="rating__star-line" transform="rotate(0)"
                                                  points="0 4,0 16"/>
                                        <polyline class="rating__star-line" transform="rotate(72)"
                                                  points="0 4,0 16"/>
                                        <polyline class="rating__star-line" transform="rotate(144)"
                                                  points="0 4,0 16"/>
                                        <polyline class="rating__star-line" transform="rotate(216)"
                                                  points="0 4,0 16"/>
                                        <polyline class="rating__star-line" transform="rotate(288)"
                                                  points="0 4,0 16"/>
                                    </g>
                                </g>
                            </svg>
                            <span class="rating__sr">5 stars—Excellent</span>
                        </label>
                    </div>
                </div>
                <p id="u_rate" style="display: none;color: red">{{ __("The data for this field is incorrect") }}</p>
                @if (!auth()->check())
                    <div class="form-group">
                        <label>{{__('dashboard.name')}}</label>
                        <input type="text" id="name" class="form-control" name="name">
                        <p id="u_name" style="display: none;color: red">{{ __("The data for this field is incorrect") }}</p>
                    </div>
                    <div class="form-group">
                        <label>{{__('dashboard.email')}}</label>
                        <input type="email" id="email" class="form-control" name="email">
                        <p id="u_email" style="display: none;color: red">{{ __("The data for this field is incorrect") }}</p>
                    </div>
                @endif
                <div class="form-group">
                    <label for="rateText">{{ __("Rate text") }}</label>
                    <textarea class="form-control w-100" name="rate_text" id="rateText" rows="4"></textarea>
                    <p id="u_rateText" style="display: none;color: red">{{ __("The data for this field is incorrect") }}</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">{{ __("Close") }}</button>
                <button type="button" onclick="rateFunction()" class="btn btn-primary btn-sm">
                    {{ __("Send rate") }}
                </button>
            </div>
        </form>
    </div>
</div>
