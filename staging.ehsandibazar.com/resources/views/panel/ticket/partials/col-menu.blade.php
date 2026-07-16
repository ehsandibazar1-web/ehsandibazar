<div class="col-lg-4 col-md-12">
    <div class="card">
        <div class="m-b-20">
            <div class="contact-grid">
                <div class="profile-header bg-dark">
                    <div class="user-name">{{ auth()->user()->name." ". auth()->user()->family }}</div>
                    <div class="name-center">{{ \App\Utility\Level::getLevel(auth()->user()->level) }}</div>
                </div>
                <img
                    src="{{ isset(auth()->user()->image[0]) ? auth()->user()->image[0]->url : url('admin/assets/images/default/noCustomer.svg')  }}"
                    class="user-img" alt="{{ auth()->user()->name }}">
                <p>
                    {{ isset(auth()->user()->email) ? auth()->user()->email : 'ایمیل خود وارد نکرده اید' }}
                </p>
                <p>
                    @if(isset(auth()->user()->whoPresenter) && !empty(auth()->user()->whoPresenter))
                        <label for="">معرف شما :</label>
                        {{ auth()->user()->whoPresenter->name." ".auth()->user()->whoPresenter->family }}
                    @endif
                </p>
                <div>
                            <span class="phone">
                                <i class="material-icons">phone</i>{{ isset(auth()->user()->mobile ) ? auth()->user()->mobile : 'موبایل خود را وارد نکرده اید' }}
                            </span>
                </div>

            </div>
        </div>
    </div>

</div>
