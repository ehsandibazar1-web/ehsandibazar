<div>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- Menu -->
        <div class="menu">
            <ul class="list">
                <li class="sidebar-user-panel active">
                    <div class="user-panel">
                        <div class=" image">
                            <img
                                    src="{{ isset(auth()->user()->image[0]) ? auth()->user()->image[0]->url : url('admin//assets/images/default/noCustomer.svg')  }}"
                                    class="img-circle user-img-circle"
                                    alt="User Image"/>
                        </div>


                    </div>
                    <div class="profile-usertitle">
                        <div
                                class="sidebar-userpic-name"> {{ isset($currentUser) ?  $currentUser->name : "" }} {{ isset($currentUser) ? $currentUser->family : ""  }}</div>
                        <div class="profile-usertitle-job ">آخرین ورود
                            : {{ verta($currentUser->lastLoginAt())->format('%d %B %Y H:i') }}</div>

                    </div>
                </li>

                {{-- start dashboard --}}
                <li class=" {{ \App\Utility\ActiveMenu::ActiveMenu(["panel"])  }} ">
                    <a href="{{ route('panel.dashboard.index')  }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>داشبورد</span>
                    </a>
                </li>
                {{-- end dashboard --}}


                <li>
                    <a href="{{ route('site.index')  }}">
                        <i class="fas fa-globe"></i>
                        <span>بازگشت به سایت</span>
                    </a>
                </li>


                @can('panel.profile.index')
                    {{-- start profile --}}
                    <li class="{{ \App\Utility\ActiveMenu::ActiveMenu(["profile"])  }}">
                        <a href="{{ route('panel.profile.index') }}">
                            <i class="fas fa-user-plus"></i>
                            <span> پروفایل من </span>
                        </a>
                    </li>
                    {{-- end profile --}}
                @endcan





                @if(
                 \Illuminate\Support\Facades\Gate::check('panel.users.index') ||
                  \Illuminate\Support\Facades\Gate::check('panel.role.index') ||
                 \Illuminate\Support\Facades\Gate::check('panel.permission.index')
                )
                    {{-- start users --}}
                    <li class="{{ \App\Utility\ActiveMenu::ActiveMenu(["users"])  }}">
                        <a href="#" onClick="return false;" class="menu-toggle">
                            <i class="fas fa-users"></i>
                            <span> کاربران </span>
                        </a>
                        <ul class="ml-menu">
                            @can('panel.users.index')
                                <li>
                                    <a href="{{ route('panel.users.index') }}">
                                        <span> فهرست کاربران </span>
                                    </a>
                                </li>
                            @endcan
                            @can('panel.role.index')
                                <li>
                                    <a href="{{ route('panel.role.index') }}">
                                        <span> فهرست نقش های کاربری </span>
                                    </a>
                                </li>
                            @endcan
                            @can('panel.permission.index')
                                <li>
                                    <a href="{{ route('panel.permission.index') }}">
                                        <span> فهرست سطوح دسترسی </span>
                                    </a>
                                </li>
                            @endcan
                        </ul>

                    </li>
                    {{-- end users --}}
                @endif




                @if(
                     \Illuminate\Support\Facades\Gate::check('panel.digitalProduct.index') ||
                      \Illuminate\Support\Facades\Gate::check('panel.digitalProduct.show') ||
                     \Illuminate\Support\Facades\Gate::check('panel.digitalProduct.add')
                                )
                    {{-- start digital-product --}}
                    <li class="{{ \App\Utility\ActiveMenu::ActiveMenu(["digital-product"])  }}">
                        <a href="#" onClick="return false;" class="menu-toggle">
                            <i class="fas fa-digital-tachograph"></i>
                            <span> محصولات دیجیتال </span>
                        </a>
                        <ul class="ml-menu">
                            @can('panel.digitalProduct.add')
                                <li>
                                    <a href="{{ route('panel.digitalProduct.add') }}">
                                        <span>افزودن به کاربر</span>
                                    </a>
                                </li>
                            @endcan
                            @can('panel.digitalProduct.index')
                                <li>
                                    <a href="{{ route('panel.digitalProduct.index') }}">
                                        <span> فهرست خرید  </span>
                                    </a>
                                </li>
                            @endcan
                        </ul>

                    </li>
                    {{-- end digital-product --}}
                @endif



                @can('panel.category.index')
                    {{-- start category --}}
                    <li class="{{ \App\Utility\ActiveMenu::ActiveMenu(["category"])  }} ">
                        <a href=" {{ route('panel.category.index')  }} ">
                            <i class="fa fa-list"></i>
                            <span> دسته بندی ها </span>
                        </a>
                    </li>
                    {{-- end category --}}
                @endcan

                @if(
                \Illuminate\Support\Facades\Gate::check('panel.product.index') ||
                \Illuminate\Support\Facades\Gate::check('panel.brand.index')
                           )
                    {{-- start digital-product --}}
                    <li class="{{ \App\Utility\ActiveMenu::ActiveMenu(["product"])  }}">
                        <a href="#" onClick="return false;" class="menu-toggle">
                            <i class="fas fa-archive"></i>
                            <span> محصولات  </span>
                        </a>
                        <ul class="ml-menu">
                            @can('panel.brand.index')
                                <li>
                                    <a href="{{ route('panel.brand.index') }}">
                                        <span> برند ها  </span>
                                    </a>
                                </li>
                            @endcan
                            @can('panel.product.index')
                                <li>
                                    <a href="{{ route('panel.product.index') }}">
                                        <span>فهرست محصولات</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>

                    </li>
                    {{-- end digital-product --}}
                @endif

                @if(\Illuminate\Support\Facades\Gate::check('panel.attributeGroup.index') ||
               \Illuminate\Support\Facades\Gate::check('panel.attributes.index')
               )
                    {{-- start attribute --}}
                    <li class="{{ \App\Utility\ActiveMenu::ActiveMenu(["attributes"])  }}">
                        <a href="#" onClick="return false;" class="menu-toggle">
                            <i class="fas fa-atlas"></i>
                            <span> ویژگی ها </span>
                        </a>
                        <ul class="ml-menu">
                            @can('panel.attributeGroup.index')
                                <li>
                                    <a href="{{ route('panel.attributeGroup.index') }}">
                                        <span> گروه بندی ویژگی ها </span>
                                    </a>
                                </li>
                            @endcan
                            @can('panel.attribute.index')
                                <li>
                                    <a href="{{ route('panel.attribute.index') }}">
                                        <span> فهرست ویژگی ها </span>
                                    </a>
                                </li>
                            @endcan
                        </ul>

                    </li>
                    {{-- end attribute --}}
                @endif


                @if(\Illuminate\Support\Facades\Gate::check('panel.order.index') ||
           \Illuminate\Support\Facades\Gate::check('panel.order.sending')
           )
                    {{-- start attribute --}}
                    <li class="{{ \App\Utility\ActiveMenu::ActiveMenu(["orders","sending","canceled","unpaid","pending"])  }}">
                        <a href="#" onClick="return false;" class="menu-toggle">
                            <i class="fas fa-euro-sign"></i>
                            <span> سفارش ها </span>
                        </a>
                        <ul class="ml-menu">
                            @can('panel.order.index')
                                <li>
                                    <a href="{{ route('panel.order.index') }}">
                                        <span>فهرست سفارشات</span>
                                    </a>
                                </li>
                            @endcan
                            @can('panel.order.sending')
                                <li>
                                    <a href="{{ route('panel.order.sending') }}">
                                        <span> ارسال شده ها </span>
                                    </a>
                                </li>
                            @endcan
                            @can('panel.order.canceled')
                                <li>
                                    <a href="{{ route('panel.order.canceled') }}">
                                        <span> لغو شده ها </span>
                                    </a>
                                </li>
                            @endcan

                            @can('panel.order.unpaid')
                                <li>
                                    <a href="{{ route('panel.order.unpaid') }}">
                                        <span> پرداخت نشده ها </span>
                                    </a>
                                </li>
                            @endcan
                            @can('panel.order.pending')
                                <li>
                                    <a href="{{ route('panel.order.pending') }}">
                                        <span>  درحال پردازش ها </span>
                                    </a>
                                </li>
                            @endcan
                        </ul>

                    </li>
                    {{-- end attribute --}}
                @endif

                @can('panel.reporting.index')
                    {{-- start favorite --}}
                    <li class="{{ \App\Utility\ActiveMenu::ActiveMenu(["reporting"])  }} ">
                        <a href=" {{ route('panel.reporting.index')  }} ">
                            <i class="fa fa-reply"></i>
                            <span>گزارش گیری</span>
                        </a>
                    </li>
                    {{-- end favorite --}}
                @endcan

                @can('panel.discount.index')
                    {{-- start favorite --}}
                    <li class="{{ \App\Utility\ActiveMenu::ActiveMenu(["discount"])  }} ">
                        <a href=" {{ route('panel.discount.index')  }} ">
                            <i class="fa fa-dollar-sign"></i>
                            <span>تخفیفات</span>
                        </a>
                    </li>
                    {{-- end favorite --}}
                @endcan

                @can('panel.favorite.index')
                    {{-- start favorite --}}
                    <li class="{{ \App\Utility\ActiveMenu::ActiveMenu(["favorite"])  }} ">
                        <a href=" {{ route('panel.favorite.index')  }} ">
                            <i class="fa fa-heart"></i>
                            <span>علاقه مندی ها</span>
                        </a>
                    </li>
                    {{-- end favorite --}}
                @endcan




                @if(\Illuminate\Support\Facades\Gate::check('panel.article.create') ||
                  \Illuminate\Support\Facades\Gate::check('panel.article.index')
                  )
                    {{-- start articles --}}
                    <li class="{{ \App\Utility\ActiveMenu::ActiveMenu(["article"])  }}">
                        <a href="#" onClick="return false;" class="menu-toggle">
                            <i class="fas fa-newspaper"></i>
                            <span> مقالات </span>
                        </a>
                        <ul class="ml-menu">
                            @can('panel.article.create')
                                <li>
                                    <a href="{{ route('panel.article.create') }}">
                                        <span> ایجاد مقاله </span>
                                    </a>
                                </li>
                            @endcan
                            @can('panel.article.index')
                                <li>
                                    <a href="{{ route('panel.article.index') }}">
                                        <span> فهرست مقالات </span>
                                    </a>
                                </li>
                            @endcan
                        </ul>

                    </li>
                    {{-- end articles --}}
                @endif


                @can('panel.ticket.index')
                    {{-- start ticket --}}
                    <li class="{{ \App\Utility\ActiveMenu::ActiveMenu(["ticket"])  }}">
                        <a href="{{ route('panel.ticket.index') }}">
                            <i class="fas fa-ticket-alt"></i>
                            <span>تیکت</span>
                        </a>
                    </li>
                @endcan

                @can('panel.exam.index')
                    <li class="{{ \App\Utility\ActiveMenu::ActiveMenu(["exams"])  }} ">
                        <a href=" {{ route('panel.exam.index')  }} ">
                            <i class="fa fa-envelope"></i>
                            <span>آزمون</span>
                        </a>
                    </li>
                @endcan


                @can('panel.contact.index')
                    {{-- start contact --}}
                    <li class="{{ \App\Utility\ActiveMenu::ActiveMenu(["contact"])  }} ">
                        <a href=" {{ route('panel.contact.index')  }} ">
                            <i class="fa fa-envelope"></i>
                            <span>پیام ها</span>
                            <span
                                    class="badge badge-danger side-nav-badge">{{ isset($countContact) ? $countContact : 0 }}</span>
                        </a>
                    </li>
                    {{-- end contact --}}
                @endcan

                @can('panel.consultation.index')
                    {{-- start consultation --}}
                    <li class="{{ \App\Utility\ActiveMenu::ActiveMenu(["consultation"])  }} ">
                        <a href=" {{ route('panel.consultation.index')  }} ">
                            <i class="fa fa-envelope"></i>
                            <span>مشاوره</span>
                            <span
                                    class="badge badge-danger side-nav-badge">{{ isset($countConsultation) ? $countConsultation : 0 }}</span>
                        </a>
                    </li>
                    {{-- end consultation --}}
                @endcan

                @can('panel.page.index')
                    {{-- start page --}}
                    <li class="{{ \App\Utility\ActiveMenu::ActiveMenu(["page"])  }} ">
                        <a href=" {{ route('panel.page.index')  }} ">
                            <i class="fa fa-list-alt"></i>
                            <span>صفحه ساز</span>
                        </a>
                    </li>
                    {{-- end page --}}
                @endcan

                @can('panel.comments.index')
                    {{-- start comments --}}
                    <li class="{{ \App\Utility\ActiveMenu::ActiveMenu(["comments"])  }} ">
                        <a href=" {{ route('panel.comments.index')  }} ">
                            <i class="fas fa-comment"></i>
                            <span>دیدگاه ها</span>
                            <span
                                    class="badge badge-danger side-nav-badge">{{ isset($countComment) ? $countComment : 0 }}</span>
                        </a>
                    </li>
                    {{-- end comments --}}
                @endcan



                @can('panel.newsLetter.index')
                    {{-- start newsLetter --}}
                    <li class="{{ \App\Utility\ActiveMenu::ActiveMenu(["newsLetter"])  }} ">
                        <a href=" {{ route('panel.newsLetter.index')  }} ">
                            <i class="fa fa-envelope"></i>
                            <span> خبرنامه </span>
                        </a>
                    </li>
                    {{-- end newsLetter --}}
                @endcan



                @can('panel.setting.index')
                    {{-- start setting --}}
                    <li class="{{ \App\Utility\ActiveMenu::ActiveMenu(["setting",'cities','tag','menu'])  }} ">
                        <a href="#" onClick="return false;" class="menu-toggle">
                            <i class="fas fa-cog"></i>
                            <span>تنظیمات</span>
                        </a>
                        <ul class="ml-menu">
                            @can('panel.cities.index')
                                <li>
                                    <a href="{{ route('panel.cities.index') }}">شهر ها</a>
                                </li>
                            @endcan
                            @can('panel.tag.index')
                                <li>
                                    <a href="{{ route('panel.tag.index') }}">برچسب ها</a>
                                </li>
                            @endcan
                            @can('panel.menu.index')
                                <li>
                                    <a href="{{ route('panel.menu.index') }}">منو ها</a>
                                </li>
                            @endcan
                            @can('panel.setting.create')
                                <li>
                                    <a href="{{ route('panel.setting.index') }}">تنظیمات سایت</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                    {{-- end setting --}}
                @endcan

            </ul>


        </div>
    </aside>


</div>
