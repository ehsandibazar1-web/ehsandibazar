{{-- لایه‌ی ظاهریِ مدرنِ پنلِ اعضا — فقط CSS، هیچ markup/منطق/لینکی تغییر نمی‌کند. همه‌ی قواعد
     زیرِ .account-page اسکوپ شده تا هدر/فوترِ سایت دست‌نخورده بماند. بعد از استایل‌های UIkit لود
     می‌شود تا آن‌ها را بازتعریف کند. برای بازگشت کافی است این include از user-style-area حذف شود. --}}
<style>
    .account-page {
        --ux-accent: #c1121f;
        --ux-accent-soft: #fde8ea;
        --ux-ink: #1f2937;
        --ux-muted: #6b7280;
        --ux-line: #e9ecf1;
        --ux-bg: #f6f7f9;
        --ux-card: #ffffff;
        --ux-radius: 16px;
        background: var(--ux-bg);
        color: var(--ux-ink);
        font-feature-settings: "ss01";
    }

    .account-page .uk-container { max-width: 1200px; }

    /* ---------- سایدبارِ منو ---------- */
    .account-page .dashboard-nav {
        background: var(--ux-card);
        border: 1px solid var(--ux-line);
        border-radius: var(--ux-radius);
        padding: 14px;
        box-shadow: 0 4px 18px rgba(17, 24, 39, .04);
        position: sticky;
        top: 90px;
    }
    .account-page .dashboard-nav .uk-nav { margin: 0; }
    .account-page .dashboard-nav .uk-nav li { margin: 2px 0; }
    .account-page .dashboard-nav .uk-nav li > a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 14px;
        border-radius: 12px;
        color: var(--ux-ink);
        font-size: 14.5px;
        font-weight: 600;
        transition: all .18s ease;
    }
    .account-page .dashboard-nav .uk-nav li > a:hover {
        background: #f3f4f6;
        color: var(--ux-accent);
    }
    .account-page .dashboard-nav .uk-nav li.uk-active > a,
    .account-page .dashboard-nav .uk-nav li.active > a {
        background: var(--ux-accent);
        color: #fff !important;
        box-shadow: 0 6px 16px rgba(193, 18, 31, .28);
    }
    .account-page .dashboard-nav .uk-nav li > a [uk-icon],
    .account-page .dashboard-nav .uk-nav li > a .uk-icon { opacity: .9; }

    /* ---------- کارت‌ها ---------- */
    .account-page .uk-card {
        border-radius: var(--ux-radius);
        border: 1px solid var(--ux-line);
        box-shadow: 0 4px 18px rgba(17, 24, 39, .04);
        background: var(--ux-card);
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .account-page .uk-card-hover:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(17, 24, 39, .10);
    }
    .account-page .uk-card-title {
        font-size: 15px;
        font-weight: 700;
        color: var(--ux-muted);
        margin-bottom: 10px;
    }
    .account-page .uk-card-body { padding: 22px; }

    /* شمارنده‌های داشبورد: عددِ بزرگ به‌جای بَجِ کوچک */
    .account-page .account-orders + .uk-grid .uk-badge,
    .account-page .uk-card .uk-badge {
        background: var(--ux-accent-soft);
        color: var(--ux-accent);
        font-size: 22px;
        font-weight: 800;
        min-width: 54px;
        height: auto;
        line-height: 1;
        padding: 12px 16px;
        border-radius: 12px;
        display: inline-block;
    }

    /* ---------- کارتِ خوش‌آمد / آلرت‌ها ---------- */
    .account-page [uk-alert], .account-page .uk-alert {
        border-radius: 14px;
        padding: 16px 18px;
        border: 1px solid transparent;
    }
    .account-page .uk-alert-success {
        background: #ecfdf5; border-color: #a7f3d0; color: #065f46;
    }
    .account-page .uk-alert-warning {
        background: #fffbeb; border-color: #fde68a; color: #92400e;
    }
    .account-page .uk-alert-danger {
        background: #fef2f2; border-color: #fecaca; color: #991b1b;
    }

    /* ---------- جدول‌ها ---------- */
    .account-page .uk-table {
        border-radius: var(--ux-radius);
        overflow: hidden;
        background: var(--ux-card);
        border: 1px solid var(--ux-line);
        box-shadow: 0 4px 18px rgba(17, 24, 39, .04);
    }
    .account-page .uk-table th {
        background: #fafbfc;
        color: var(--ux-muted);
        font-weight: 700;
        font-size: 12.5px;
        text-transform: none;
        padding: 14px 16px;
        border-bottom: 1px solid var(--ux-line);
    }
    .account-page .uk-table td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid var(--ux-line);
    }
    .account-page .uk-table-hover tbody tr:hover { background: #fafbfc; }
    .account-page .uk-table tbody tr:last-child td { border-bottom: 0; }

    /* ---------- فرم‌ها ---------- */
    .account-page .uk-form-label {
        font-weight: 600; font-size: 13.5px; color: var(--ux-ink); margin-bottom: 6px;
    }
    .account-page .uk-input,
    .account-page .uk-select,
    .account-page textarea.uk-textarea {
        border-radius: 11px !important;
        border: 1px solid var(--ux-line) !important;
        height: 44px;
        background: #fff;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .account-page .uk-input:focus,
    .account-page .uk-select:focus,
    .account-page textarea.uk-textarea:focus {
        border-color: var(--ux-accent) !important;
        box-shadow: 0 0 0 3px rgba(193, 18, 31, .12) !important;
        outline: none;
    }

    /* ---------- دکمه‌ها ---------- */
    .account-page .uk-button {
        border-radius: 11px;
        font-weight: 700;
        padding: 0 20px;
        line-height: 42px;
        transition: filter .15s ease, transform .1s ease;
    }
    .account-page .uk-button:active { transform: translateY(1px); }
    .account-page .uk-button-primary { background: var(--ux-accent); }
    .account-page .uk-button-primary:hover { background: var(--ux-accent); filter: brightness(1.08); }
    .account-page .uk-button-danger { background: #ef4444; }
    .account-page .uk-button-danger:hover { filter: brightness(1.06); }
    .account-page .uk-button-default {
        border: 1px solid var(--ux-line); background: #fff; color: var(--ux-ink);
    }
    .account-page .uk-button-default:hover { background: #f3f4f6; }

    /* ---------- برچسب‌ها ---------- */
    .account-page .uk-label {
        border-radius: 8px; font-weight: 700; padding: 5px 10px;
    }
    .account-page .uk-label-danger { background: #ef4444; }

    /* عنوان‌های بخش */
    .account-page h4 { font-weight: 800; color: var(--ux-ink); }

    @media (max-width: 640px) {
        .account-page .dashboard-nav { position: static; margin-bottom: 16px; }
        .account-page .uk-card-body { padding: 18px; }
    }
</style>
