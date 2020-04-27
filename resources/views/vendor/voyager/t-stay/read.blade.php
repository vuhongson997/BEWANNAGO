
@if ($stay->host_id == Auth::id() || Auth::user()->role_id == "1")

    @extends('voyager::master')

    @section('page_title', __('voyager::generic.view').' '.$dataType->getTranslatedAttribute('display_name_singular'))

    @section('page_header')
        <h1 class="page-title">
            <i class="{{ $dataType->icon }}"></i> {{ __('voyager::generic.viewing') }} {{ ucfirst($dataType->getTranslatedAttribute('display_name_singular')) }} &nbsp;

            @can('edit', $dataTypeContent)
                <a href="{{ route('voyager.'.$dataType->slug.'.edit', $dataTypeContent->getKey()) }}" class="btn btn-info">
                    <span class="glyphicon glyphicon-pencil"></span>&nbsp;
                    {{ __('voyager::generic.edit') }}
                </a>
            @endcan
            @can('delete', $dataTypeContent)
                @if($isSoftDeleted)
                    <a href="{{ route('voyager.'.$dataType->slug.'.restore', $dataTypeContent->getKey()) }}" title="{{ __('voyager::generic.restore') }}" class="btn btn-default restore" data-id="{{ $dataTypeContent->getKey() }}" id="restore-{{ $dataTypeContent->getKey() }}">
                        <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.restore') }}</span>
                    </a>
                @else
                    <a href="javascript:;" title="{{ __('voyager::generic.delete') }}" class="btn btn-danger delete" data-id="{{ $dataTypeContent->getKey() }}" id="delete-{{ $dataTypeContent->getKey() }}">
                        <i class="voyager-trash"></i> <span class="hidden-xs hidden-sm">{{ __('voyager::generic.delete') }}</span>
                    </a>
                @endif
            @endcan

            <a href="{{ route('voyager.'.$dataType->slug.'.index') }}" class="btn btn-warning">
                <span class="glyphicon glyphicon-list"></span>&nbsp;
                {{ __('voyager::generic.return_to_list') }}
            </a>
        </h1>
        @include('voyager::multilingual.language-selector')
    @stop

    @section('content')
        
            <div class="page-content read container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-bordered" style="padding-bottom:5px;">
                            <!-- form start -->
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Tên chỗ thuê</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $stay->stay_name }}</p>
                            </div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Ngôn ngữ</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                {{ $stay->lang }}</div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Giá tiền</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ number_format($stay->price) }} VNĐ</p>
                            </div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Giảm giá</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ number_format($stay->discount) }} VNĐ</p>
                            </div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Mô tả</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                @foreach($dataType->readRows as $row)
                                    @php                        
                                        if ($dataTypeContent->{$row->field.'_read'}) {
                                            $dataTypeContent->{$row->field} = $dataTypeContent->{$row->field.'_read'};
                                        }
                                    @endphp
                                    @if($row->type == 'rich_text_box')
                                        @include('voyager::multilingual.input-hidden-bread-read')
                                        {!! $dataTypeContent->{$row->field} !!}   
                                    @endif
                                @endforeach
                            </div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Loại phòng</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $stay->type->code_name }}</p>
                            </div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Số lượng khách</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $stay->guest_count}}</p>
                            </div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Số lượng giường</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $stay->bed_count}}</p>
                            </div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Số lượng phòng tắm</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $stay->bath_count}}</p>
                            </div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Wifi</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>
                                    @if ($stay->wifi == 1)
                                        Không
                                    @else
                                        Có
                                    @endif
                                </p>
                            </div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Hút thuốc</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>
                                    @if ($stay->smoking == 1)
                                        Không
                                    @else
                                        Có
                                    @endif
                                </p>
                            </div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Máy lạnh</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>
                                    @if ($stay->cooler == 1)
                                        Không
                                    @else
                                        Có
                                    @endif
                                </p>
                            </div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Tủ lạnh</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>
                                    @if ($stay->refrigerator == 1)
                                        Không
                                    @else
                                        Có
                                    @endif
                                </p>
                            </div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Bể bơi</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>
                                    @if ($stay->pool == 1)
                                        Không
                                    @else
                                        Có
                                    @endif
                                </p>
                            </div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Bếp ăn</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>
                                    @if ($stay->kitchen == 1)
                                        Không
                                    @else
                                        Có
                                    @endif
                                </p>
                            </div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Địa chỉ</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $address->area }}</p>
                            </div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Tỉnh/Thành phố</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $address->province->name  }}</p>
                            </div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Quận/Huyện</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $address->district_name->name }}</p>
                            </div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Phường/Xã</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $address->ward->name ?? '' }}</p>
                            </div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Tên Quản lí</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $user->name }}</p>
                            </div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Hình ảnh</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                @foreach(json_decode($roomGallery->img_url) as $file)
                                    <img class="img-responsive" style="margin-left: 1.2em;
                                    float: left;
                                    margin-bottom: 1.2em;"
                                        src="{{ filter_var($file, FILTER_VALIDATE_URL) ? $file : Voyager::image($file) }}" width="24%">
                                @endforeach
                            </div><!-- panel-body -->
                            <hr style="margin:0;">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Ngày tạo</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $stay->created_at }}</p>
                            </div><!-- panel-body -->
                        </div>
                        
                    </div>
                </div>
            </div>

            {{-- Single delete modal --}}
            <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"><i class="voyager-trash"></i> {{ __('voyager::generic.delete_question') }} {{ strtolower($dataType->getTranslatedAttribute('display_name_singular')) }}?</h4>
                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('voyager.'.$dataType->slug.'.index') }}" id="delete_form" method="POST">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <input type="submit" class="btn btn-danger pull-right delete-confirm"
                                    value="{{ __('voyager::generic.delete_confirm') }} {{ strtolower($dataType->getTranslatedAttribute('display_name_singular')) }}">
                            </form>
                            <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        
    @stop

    @section('javascript')
        @if ($isModelTranslatable)
            <script>
                $(document).ready(function () {
                    $('.side-body').multilingual();
                });
            </script>
        @endif
        <script>
            var deleteFormAction;
            $('.delete').on('click', function (e) {
                var form = $('#delete_form')[0];

                if (!deleteFormAction) {
                    // Save form action initial value
                    deleteFormAction = form.action;
                }

                form.action = deleteFormAction.match(/\/[0-9]+$/)
                    ? deleteFormAction.replace(/([0-9]+$)/, $(this).data('id'))
                    : deleteFormAction + '/' + $(this).data('id');

                $('#delete_modal').modal('show');
            });

        </script>
    @stop
@endif

