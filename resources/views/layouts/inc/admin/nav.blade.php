<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="navbar-brand-wrapper d-flex justify-content-center">
          <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
              <a class="navbar-brand brand-logo" href="{{ url('/') }}"><img src="{{ asset('images/logo.svg') }}" alt="logo" /></a>
              <a class="navbar-brand brand-logo-mini" href="{{ url('/') }}"><img src="{{ asset('images/logo-mini.svg') }}"
                      alt="logo" /></a>
              <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                  <span class="mdi mdi-sort-variant"></span>
              </button>
          </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
          <ul class="navbar-nav mr-lg-4 w-100">
              <li class="nav-item nav-search d-none d-lg-block w-100">
                  <div class="input-group">
                      <div class="input-group-prepend">
                          <span class="input-group-text" id="search">
                              <i class="mdi mdi-magnify"></i>
                          </span>
                      </div>
                      <input type="text" class="form-control" placeholder="{{ __('messages.search_now') }}" aria-label="search"
                          aria-describedby="search">
                  </div>
              </li>
          </ul>
          <ul class="navbar-nav navbar-nav-right">

              {{-- Language Switcher --}}
              <li class="nav-item dropdown mr-1">
                  <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center"
                      id="langDropdown" href="#" data-toggle="dropdown">
                      <i class="mdi mdi-web mx-0"></i>
                  </a>
                 
              </li>

              <li class="nav-item dropdown mr-1">
                  <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center"
                      id="messageDropdown" href="#" data-toggle="dropdown">
                      <i class="mdi mdi-message-text mx-0"></i>
                      <span class="count"></span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="messageDropdown">
                      <p class="mb-0 font-weight-normal float-left dropdown-header">{{ __('messages.messages') }}</p>
                      <a class="dropdown-item" href="#">
                          <div class="item-thumbnail">
                              <img src="{{ asset('images/faces/face4.jpg') }}" alt="image" class="profile-pic">
                          </div>
                          <div class="item-content flex-grow">
                              <h6 class="ellipsis font-weight-normal">David Grey
                              </h6>
                              <p class="font-weight-light small-text text-muted mb-0">
                                  The meeting is cancelled
                              </p>
                          </div>
                      </a>
                      <a class="dropdown-item" href="#">
                          <div class="item-thumbnail">
                              <img src="{{ asset('images/faces/face2.jpg') }}" alt="image" class="profile-pic">
                          </div>
                          <div class="item-content flex-grow">
                              <h6 class="ellipsis font-weight-normal">Tim Cook
                              </h6>
                              <p class="font-weight-light small-text text-muted mb-0">
                                  New product launch
                              </p>
                          </div>
                      </a>
                      <a class="dropdown-item" href="#">
                          <div class="item-thumbnail">
                              <img src="{{ asset('images/faces/face3.jpg') }}" alt="image" class="profile-pic">
                          </div>
                          <div class="item-content flex-grow">
                              <h6 class="ellipsis font-weight-normal"> Johnson
                              </h6>
                              <p class="font-weight-light small-text text-muted mb-0">
                                  Upcoming board meeting
                              </p>
                          </div>
                      </a>
                  </div>
              </li>
              <li class="nav-item dropdown mr-4">
                  <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center notification-dropdown"
                      id="notificationDropdown" href="#" data-toggle="dropdown">
                      <i class="mdi mdi-bell mx-0"></i>
                      <span class="count"></span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="notificationDropdown">
                      <a class="dropdown-item" href="#">
                          <div class="item-thumbnail">
                              <div class="item-icon bg-success">
                              </div>
                          </div>
                          <div class="item-content">
                              <h6 class="font-weight-normal">Application Error</h6>
                              <p class="font-weight-light small-text mb-0 text-muted">
                                  Just now
                              </p>
                          </div>
                      </a>
                      <a class="dropdown-item" href="#">
                          <div class="item-thumbnail">
                              <div class="item-icon bg-warning">
                                  <i class="mdi mdi-settings mx-0"></i>
                              </div>
                          </div>
                          <div class="item-content">
                              <h6 class="font-weight-normal">Settings</h6>
                              <p class="font-weight-light small-text mb-0 text-muted">
                                  Private message
                              </p>
                          </div>
                      </a>
                      <a class="dropdown-item" href="#">
                          <div class="item-thumbnail">
                              <div class="item-icon bg-info">
                                  <i class="mdi mdi-account-box mx-0"></i>
                              </div>
                          </div>
                          <div class="item-content">
                              <h6 class="font-weight-normal">New user registration</h6>
                              <p class="font-weight-light small-text mb-0 text-muted">
                                  2 days ago
                              </p>
                          </div>
                      </a>
                  </div>
              </li>
              <li class="nav-item nav-profile dropdown">
                  <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
                      <img src="{{ asset('images/faces/face5.jpg') }}" alt="profile" />
                      <span class="nav-profile-name">{{ auth()->user()->name ?? '' }}</span>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">

                      <a class="dropdown-item" href="#">
                          <i class="mdi mdi-account-circle text-primary"></i> {{ __('messages.profile') }}
                      </a>
                      <div class="dropdown-divider"></div>
                      <form method="POST" action="">
                          @csrf
                          <button type="submit" class="dropdown-item">
                              <i class="mdi mdi-logout text-primary"></i> {{ __('messages.logout') }}
                          </button>
                      </form>
                  </div>

              </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
              data-toggle="offcanvas">
              <span class="mdi mdi-menu"></span>
          </button>
      </div>
  </nav>