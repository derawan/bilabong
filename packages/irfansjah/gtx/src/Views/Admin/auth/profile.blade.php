@extends('gtx::Admin.layouts.app')

@section('content')
<div class="row">

    <!-- /.col -->
    <div class="col-md-12">
        <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-black" style="background: url('{{asset('assets/themes/adminlte/img/photo1.png')}}') center center;">
              <h3 class="widget-user-username">{{$user->name}}</h3>
              <h5 class="widget-user-desc">{{$user->email}}</h5>
            </div>
            <div class="widget-user-image">
              <img class="img-circle" src="{{$user->getavatar(false)}}" alt="User Avatar">
            </div>
            <div class="box-body" style="padding-top:40px;">

                @include('gtx::Admin.partials.flash')
                {{-- @if (count($errors)>0)

                @endif --}}
                <div class="row">
                    <div class="col-md-12" style="text-align:center">
                        <h3 class="profile-username text-center">{{$user->name}}</h3>

                        <p class="text-muted text-center">{{$user->email}}</p>

                        <strong><i class="fa fa-pencil margin-r-5"></i> {{__("Role")}}</strong>

                        <p>

                            <span class="label label-danger">{{$user->getUserRole()}}</span>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 hidden">

                    </div>
                    <!-- /.col -->
                    <div class="col-md-12">

                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                              {{--
                               --}}
                              <li class="active"><a href="#settings" data-toggle="tab"><i class="fa fa-clone"></i> {{__("Change Password")}}</a></li>
                              <li><a href="#avatar" data-toggle="tab"><i class="fa fa-clone"></i> {{__("Change Avatar")}}</a></li>
                              <li><a href="#timeline" data-toggle="tab"><i class="fa fa-clone"></i> {{__("Timeline")}}</a></li>
                              <li><a href="#activity" data-toggle="tab"><i class="fa fa-clone"></i> {{__("Activity")}}</a></li>
                            </ul>
                            <div class="tab-content">
                              <div class=" tab-pane" id="activity">
                                <!-- Post -->
                                <div class="post">
                                  <div class="user-block">
                                    <img class="img-circle img-bordered-sm" src="{{asset('assets/themes/adminlte/img/user1-128x128.jpg')}}" alt="user image">
                                        <span class="username">
                                          <a href="#">Jonathan Burke Jr.</a>
                                          <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                                        </span>
                                    <span class="description">Shared publicly - 7:30 PM today</span>
                                  </div>
                                  <!-- /.user-block -->
                                  <p>
                                    Lorem ipsum represents a long-held tradition for designers,
                                    typographers and the like. Some people hate it and argue for
                                    its demise, but others ignore the hate as they create awesome
                                    tools to help create filler text for everyone from bacon lovers
                                    to Charlie Sheen fans.
                                  </p>
                                  <ul class="list-inline">
                                    <li><a href="#" class="link-black text-sm"><i class="fa fa-share margin-r-5"></i> Share</a></li>
                                    <li><a href="#" class="link-black text-sm"><i class="fa fa-thumbs-o-up margin-r-5"></i> Like</a>
                                    </li>
                                    <li class="pull-right">
                                      <a href="#" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i> Comments
                                        (5)</a></li>
                                  </ul>

                                  <input class="form-control input-sm" type="text" placeholder="Type a comment">
                                </div>
                                <!-- /.post -->


                              </div>
                              <!-- /.tab-pane -->
                              <div class="tab-pane" id="timeline">
                                <!-- The timeline -->
                                <ul class="timeline timeline-inverse">
                                  <!-- timeline time label -->
                                  <li class="time-label">
                                        <span class="bg-red">
                                          10 Feb. 2014
                                        </span>
                                  </li>
                                  <!-- /.timeline-label -->
                                  <!-- timeline item -->
                                  <li>
                                    <i class="fa fa-envelope bg-blue"></i>

                                    <div class="timeline-item">
                                      <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                                      <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                                      <div class="timeline-body">
                                        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                                        weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                        jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                                        quora plaxo ideeli hulu weebly balihoo...
                                      </div>
                                      <div class="timeline-footer">
                                        <a class="btn btn-primary btn-xs">Read more</a>
                                        <a class="btn btn-danger btn-xs">Delete</a>
                                      </div>
                                    </div>
                                  </li>
                                  <!-- END timeline item -->

                                  <li>
                                    <i class="fa fa-clock-o bg-gray"></i>
                                  </li>
                                </ul>
                              </div>
                              <!-- /.tab-pane -->

                              <div class="active tab-pane" id="settings">
                              <form class="form-horizontal" action="{{$route["uppass"]}}" method="POST">
                                @csrf
                              <input type="hidden" name="uid" value="{{$xid}}">
                                <div class="callout callout-info">
                                    <h4>{{__('Change Password')}}</h4>

                                    <p>{{_('To change your password. fill in these input belows')}}</p>
                                </div>
                                  @if(Auth::user()->id==$user->id)
                                  <div class="form-group @error('prevPassword') has-error @enderror">
                                    <label for="inputPrevPassword" class="col-sm-2 control-label">{{__("Previous Password")}}</label>

                                    <div class="col-sm-10">
                                      <input type="password" class="form-control" id="inputPrevPassword" name="prevPassword" placeholder="{{__("Previous Password")}}">
                                      @error('prevPassword')
                                            <span class="help-block" role="alert">
                                                <strong>{{ __($message) }}</strong>
                                            </span>
                                      @enderror
                                    </div>

                                  </div>
                                  @endif
                                  <div class="form-group @error('password') has-error @enderror">
                                    <label for="inputNewPassword" class="col-sm-2 control-label">{{__("New Password")}}</label>

                                    <div class="col-sm-10">
                                      <input type="password" class="form-control" id="inputNewPassword" name="password" placeholder="{{__("New Password")}}">
                                        @error('password')
                                            <span class="help-block" role="alert">
                                                <strong>{{ __($message) }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label for="inputConfirmPassword" class="col-sm-2 control-label">Confirm Password</label>

                                    <div class="col-sm-10">
                                      <input type="password" class="form-control" id="inputConfirmPassword" name="password_confirmation" placeholder="{{__("Confirm Password")}}">
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <div class="col-md-2">
                                        <a class="btn btn-danger" href="{{$route["upback"]}}">Back</a>
                                      </div>
                                      <div class="col-md-10">
                                        <button type="submit" class="btn btn-danger pull-right">{{__("Submit !!")}}</button>
                                    </div>

                                  </div>
                                </form>
                              </div>
                              <!-- /.tab-pane -->

                              <div class="tab-pane" id="avatar">
                                  <form action="{{$route["upav"]}}" class="form-horizontal" enctype="multipart/form-data" method="post">
                                      @csrf
                                      <input type="hidden" name="uid" value="{{$xid}}">
                                      <div class="callout callout-info">
                                        <h4>{{__('Change Avatar')}}</h4>

                                        <p>{{_('To change your avatar. pick your picture. Upload it using this input belows')}}</p>
                                    </div>
                                      <div class="form-group row">
                                        <label for="avatar" class="col-md-4 col-form-label text-md-right">{{ __('Avatar (optional)') }}</label>

                                        <div class="col-md-8">
                                            <input id="avatar" type="file" class="form-control" name="avatar">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                          <div class="col-md-2">
                                            <a class="btn btn-danger" href="{{$route["upback"]}}">Back</a>
                                          </div>
                                          <div class="col-md-10">
                                            <button type="submit" class="btn btn-danger pull-right">{{__("Submit !!")}}</button>
                                          </div>
                                    </div>


                                  </form>
                              </div>
                            </div>
                            <!-- /.tab-content -->
                          </div>

                    </div>
                    <!-- /.col -->
                  </div>


            </div>
            <div class="box-footer">

            </div>
          </div>



      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
@endsection
