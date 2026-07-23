{{-- لایه‌ی ظاهریِ مدرنِ پنلِ اعضا — فقط CSS، هیچ markup/منطق/لینکی تغییر نمی‌کند. همه‌ی قواعد
     زیرِ .profile-user-page اسکوپ شده تا هدر/فوترِ سایت دست‌نخورده بماند. بعد از now-ui-kit
     (main.css) لود می‌شود تا آن را بازتعریف کند. برای بازگشت: این include از styles حذف شود. --}}
<style>
    .profile-user-page {
        --ux-accent: #d4af3a;
        --ux-accent-2: #b8912f;
        --ux-accent-soft: #faf3da;
        --ux-ink: #1f2937;
        --ux-muted: #6b7280;
        --ux-line: #ecedf1;
        --ux-bg: #f5f6f8;
        --ux-card: #ffffff;
        --ux-radius: 16px;
        background: var(--ux-bg) !important;
        color: var(--ux-ink);
        padding-block: 26px;
    }
    .profile-user-page .container.wrapper { max-width: 1200px; }

    /* ---------- کارتِ پروفایل (سایدبار) ---------- */
    .profile-user-page .profile-box {
        background: var(--ux-card);
        border: 1px solid var(--ux-line);
        border-radius: var(--ux-radius);
        box-shadow: 0 6px 22px rgba(17,24,39,.06);
        overflow: hidden;
    }
    .profile-user-page .profile-box-header {
        background: linear-gradient(135deg, var(--ux-accent), var(--ux-accent-2));
        padding: 26px 18px 40px;
        position: relative;
    }
    .profile-user-page .profile-box-avatar img {
        width: 96px; height: 96px; border-radius: 50%;
        object-fit: cover; border: 4px solid #fff;
        box-shadow: 0 6px 18px rgba(0,0,0,.18);
        background: #fff;
    }
    .profile-user-page .profile-box-btn-edit {
        background: #fff; color: var(--ux-accent);
        width: 34px; height: 34px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        box-shadow: 0 3px 10px rgba(0,0,0,.15);
    }
    .profile-user-page .profile-box-username {
        text-align: center; font-weight: 800; font-size: 16px;
        margin-top: -18px; padding: 22px 12px 8px; color: var(--ux-ink);
    }
    .profile-user-page .profile-box-tabs { display: flex; gap: 8px; padding: 8px 14px 16px; }
    .profile-user-page .profile-box-tab {
        flex: 1; text-align: center; border-radius: 10px; padding: 9px 6px;
        font-size: 12.5px; font-weight: 700; border: 1px solid var(--ux-line);
        color: var(--ux-ink); transition: all .16s ease;
    }
    .profile-user-page .profile-box-tab:hover { background: #f3f4f6; color: var(--ux-accent); }
    .profile-user-page .profile-box-tab--sign-out:hover { background: #fef2f2; color: #dc2626; }

    /* ---------- منوی کناری ---------- */
    .profile-user-page .profile-menu {
        margin-top: 16px; background: var(--ux-card);
        border: 1px solid var(--ux-line); border-radius: var(--ux-radius);
        box-shadow: 0 6px 22px rgba(17,24,39,.06); overflow: hidden;
    }
    .profile-user-page .profile-menu-header {
        font-weight: 800; font-size: 14px; padding: 15px 18px;
        border-bottom: 1px solid var(--ux-line); color: var(--ux-muted);
    }
    .profile-user-page .profile-menu-items { list-style: none; margin: 0; padding: 8px; }
    .profile-user-page .profile-menu-items li { margin: 2px 0; }
    .profile-user-page .profile-menu-items li a {
        display: flex; align-items: center; gap: 11px;
        padding: 11px 14px; border-radius: 11px;
        color: var(--ux-ink); font-size: 14px; font-weight: 600;
        transition: all .16s ease;
    }
    .profile-user-page .profile-menu-items li a i { font-size: 17px; opacity: .85; }
    .profile-user-page .profile-menu-items li a:hover { background: #f3f4f6; color: var(--ux-accent); }
    .profile-user-page .profile-menu-items li a.active {
        background: var(--ux-accent); color: #4a3a00 !important;
        box-shadow: 0 6px 16px rgba(184,145,47,.26);
    }

    /* منوی موبایلِ collapse */
    .profile-user-page .responsive-profile-menu .card.list-p {
        border: 1px solid var(--ux-line); border-radius: var(--ux-radius);
        box-shadow: 0 6px 22px rgba(17,24,39,.06); padding: 8px;
    }
    .profile-user-page .responsive-profile-menu .dropdown-item {
        display: flex; align-items: center; gap: 10px;
        border-radius: 10px; padding: 11px 14px; font-weight: 600;
    }
    .profile-user-page .responsive-profile-menu .dropdown-item.active,
    .profile-user-page .responsive-profile-menu .dropdown-item:hover { background: #f3f4f6; color: var(--ux-accent); }
    .profile-user-page .responsive-profile-menu .btn-primary {
        background: var(--ux-accent); border: 0; border-radius: 11px; font-weight: 700; padding: 11px;
    }

    /* ---------- کارت‌های محتوا ---------- */
    .profile-user-page .content-section {
        background: var(--ux-card); border: 1px solid var(--ux-line);
        border-radius: var(--ux-radius); box-shadow: 0 6px 22px rgba(17,24,39,.06);
        padding: 22px; margin-bottom: 20px;
    }
    .profile-user-page .title-tab-content {
        font-size: 17px; font-weight: 800; color: var(--ux-ink);
        margin: 6px 0 14px; padding-right: 12px; position: relative;
    }
    .profile-user-page .title-tab-content::before {
        content: ""; position: absolute; right: 0; top: 4px; bottom: 4px;
        width: 4px; border-radius: 4px; background: var(--ux-accent);
    }

    /* ---------- جدول‌ها ---------- */
    .profile-user-page table.cart-page-table,
    .profile-user-page .table {
        width: 100%; background: var(--ux-card);
        border-radius: var(--ux-radius); overflow: hidden;
        border: 1px solid var(--ux-line);
    }
    .profile-user-page .cart-page-table th,
    .profile-user-page .table th {
        background: #fafbfc; color: var(--ux-muted);
        font-weight: 700; font-size: 12.5px; padding: 13px 14px;
        border-bottom: 1px solid var(--ux-line);
    }
    .profile-user-page .cart-page-table td,
    .profile-user-page .table td {
        padding: 13px 14px; vertical-align: middle;
        border-bottom: 1px solid var(--ux-line);
    }
    .profile-user-page .cart-page-table tr:last-child td { border-bottom: 0; }

    /* ---------- دکمه‌ها ---------- */
    .profile-user-page .btn-link-border,
    .profile-user-page .form-account-link {
        display: inline-block; border: 1.5px solid var(--ux-accent);
        color: var(--ux-accent); border-radius: 11px; padding: 9px 22px;
        font-weight: 700; background: transparent; transition: all .16s ease;
    }
    .profile-user-page .btn-link-border:hover,
    .profile-user-page .form-account-link:hover { background: var(--ux-accent); color: #4a3a00; }
    .profile-user-page .btn-primary { background: var(--ux-accent); border-color: var(--ux-accent); color: #4a3a00; border-radius: 11px; font-weight: 700; }
    .profile-user-page .btn { border-radius: 11px; font-weight: 700; }

    /* ---------- فرم‌ها ---------- */
    .profile-user-page .form-control,
    .profile-user-page input[type="text"],
    .profile-user-page input[type="email"],
    .profile-user-page input[type="password"],
    .profile-user-page select,
    .profile-user-page textarea {
        border-radius: 11px !important; border: 1px solid var(--ux-line) !important;
        padding: 10px 14px; background: #fff; transition: border-color .15s, box-shadow .15s;
    }
    .profile-user-page .form-control:focus,
    .profile-user-page input:focus,
    .profile-user-page select:focus,
    .profile-user-page textarea:focus {
        border-color: var(--ux-accent) !important;
        box-shadow: 0 0 0 3px rgba(184,145,47,.12) !important; outline: none;
    }

    /* ---------- کارتِ محصولِ دیجیتال ---------- */
    .profile-user-page .product-box {
        background: var(--ux-card); border: 1px solid var(--ux-line);
        border-radius: 14px; overflow: hidden; transition: transform .16s, box-shadow .16s;
    }
    .profile-user-page .product-box:hover {
        transform: translateY(-3px); box-shadow: 0 12px 28px rgba(17,24,39,.10);
    }
    .profile-user-page .price-value, .profile-user-page .price-value-wrapper { font-weight: 800; color: var(--ux-accent); }

    /* آواتار/آیتم علاقه‌مندی */
    .profile-user-page .profile-recent-fav-row {
        border: 1px solid var(--ux-line); border-radius: 12px;
        padding: 10px 12px; margin-bottom: 8px; background: #fff;
    }

    @media (max-width: 991px) {
        .profile-user-page .profile-page-aside { margin-bottom: 18px; }
    }

    /* ---------- دو صفحه‌ی legacy با ساختارِ UIkit (مزایده‌ها/پرداخت‌ها) ---------- */
    .account-page { background: #f5f6f8; padding-block: 22px; }
    .account-page .uk-table {
        background: #fff; border: 1px solid #ecedf1; border-radius: 16px; overflow: hidden;
        box-shadow: 0 6px 22px rgba(17,24,39,.06);
    }
    .account-page .uk-table th { background: #fafbfc; color: #6b7280; font-weight: 700; padding: 13px 14px; }
    .account-page .uk-table td { padding: 13px 14px; }
    .account-page .uk-button { border-radius: 11px; font-weight: 700; }
    .account-page .uk-button-primary { background: #d4af3a; }
    .account-page .uk-alert-warning { border-radius: 14px; background: #fffbeb; color: #92400e; }
</style>
