<?php include 'includes/header.php'; ?>

<style>
    /* ============================================================
   PRODUCTS PAGE — aligned to index.css design system
   Tokens: --primary-color #1e3a5f / --secondary-color #87CEEB
           --accent-color #FFD700 / --warm #e2e8f0 / radius 2px
============================================================ */

    /* ---- Page Hero (matches hp-hero pattern) ---- */
    .shop-hero {
        position: relative;
        background: linear-gradient(140deg, var(--primary-color) 0%, #192f4e 55%, var(--primary-light) 100%);
        color: var(--white);
        padding: 3.5rem 0 5.5rem;
        overflow: hidden;
    }

    .shop-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
        opacity: 0.5;
        pointer-events: none;
    }

    .shop-hero-blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.15;
        pointer-events: none;
    }

    .shop-blob-1 {
        width: 420px;
        height: 420px;
        background: var(--secondary-color);
        top: -100px;
        left: -80px;
    }

    .shop-blob-2 {
        width: 300px;
        height: 300px;
        background: var(--primary-light);
        bottom: 0;
        right: -40px;
    }

    .shop-hero-wave {
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        line-height: 0;
        pointer-events: none;
    }

    .shop-hero-wave svg {
        display: block;
        width: 100%;
        height: 55px;
    }

    .shop-hero-inner {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 2rem;
        position: relative;
        z-index: 1;
    }

    .shop-breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        list-style: none;
        margin-bottom: 1.25rem;
    }

    .shop-breadcrumb li {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.5);
    }

    .shop-breadcrumb li a {
        color: rgba(255, 255, 255, 0.65);
        text-decoration: none;
        transition: color var(--transition);
    }

    .shop-breadcrumb li a:hover {
        color: var(--secondary-color);
    }

    .shop-breadcrumb li.active {
        color: var(--secondary-color);
        font-weight: 600;
    }

    .shop-breadcrumb-sep {
        color: rgba(255, 255, 255, 0.25);
        font-size: 0.65rem;
    }

    .shop-hero-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--secondary-color);
        margin-bottom: 0.85rem;
    }

    .shop-eyebrow-dot {
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: var(--secondary-color);
        box-shadow: 0 0 0 3px rgba(135, 206, 235, 0.25);
        animation: dot-pulse 2.5s ease-in-out infinite;
    }

    @keyframes dot-pulse {

        0%,
        100% {
            box-shadow: 0 0 0 3px rgba(135, 206, 235, 0.25);
        }

        50% {
            box-shadow: 0 0 0 6px rgba(135, 206, 235, 0.1);
        }
    }

    .shop-hero-title {
        font-size: clamp(1.8rem, 4vw, 2.8rem);
        font-weight: 800;
        line-height: 1.08;
        letter-spacing: -0.03em;
        margin-bottom: 0.6rem;
    }

    .shop-hero-title span {
        color: var(--secondary-color);
        position: relative;
    }

    .shop-hero-title span::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: -3px;
        width: 100%;
        height: 2px;
        background: var(--secondary-color);
        opacity: 0.35;
        border-radius: 2px;
    }

    .shop-hero-sub {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.6);
        line-height: 1.7;
    }

    /* ---- Main Layout ---- */
    .shop-wrap {
        max-width: 1280px;
        margin: 0 auto;
        padding: 2.5rem 2rem 4rem;
        display: grid;
        grid-template-columns: 264px 1fr;
        gap: 2rem;
        align-items: start;
    }

    /* ============================================================
   FILTER SIDEBAR — matches hp-feature-card style
============================================================ */
    .shop-sidebar {
        position: sticky;
        top: 1.5rem;
    }

    .sidebar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .sidebar-title {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: var(--accent);
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(30, 58, 95, 0.08);
        padding: 0.35rem 0.85rem;
        border-radius: 2px;
    }

    .sidebar-clear {
        font-size: 0.72rem;
        font-weight: 600;
        color: var(--text-muted);
        background: none;
        border: 1px solid var(--warm);
        border-radius: 2px;
        padding: 0.3rem 0.7rem;
        cursor: pointer;
        font-family: var(--font);
        transition: all var(--transition);
    }

    .sidebar-clear:hover {
        border-color: var(--accent);
        color: var(--accent);
    }

    /* Filter block — matches hp-feature-card */
    .filter-block {
        background: var(--white);
        border: 1px solid var(--warm);
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 0.75rem;
        transition: all var(--transition);
    }

    .filter-block:hover {
        border-color: var(--secondary-color);
    }

    .filter-block-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.85rem 1.1rem;
        cursor: pointer;
        user-select: none;
        border-bottom: 1px solid var(--warm);
        transition: background var(--transition);
    }

    .filter-block-head:hover {
        background: var(--light-bg);
    }

    .filter-block-head.collapsed {
        border-bottom: none;
    }

    .filter-block-label {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-dark);
    }

    .filter-block-head i {
        font-size: 0.75rem;
        color: var(--text-muted);
        transition: transform var(--transition);
    }

    .filter-block-head.collapsed i {
        transform: rotate(-90deg);
    }

    .filter-block-body {
        padding: 0.85rem 1.1rem;
    }

    .filter-block-body.hidden {
        display: none;
    }

    /* Active tags inside filter block */
    .active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
        padding: 0.75rem 1.1rem;
        border-bottom: 1px solid var(--warm);
    }

    .active-filters:empty {
        display: none;
    }

    .filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.55rem;
        background: rgba(30, 58, 95, 0.08);
        color: var(--accent);
        border-radius: 2px;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .filter-tag button {
        background: none;
        border: none;
        cursor: pointer;
        color: var(--accent);
        font-size: 0.6rem;
        padding: 0;
        opacity: 0.6;
        line-height: 1;
        transition: opacity var(--transition);
    }

    .filter-tag button:hover {
        opacity: 1;
    }

    /* Checkbox / radio rows */
    .filter-check {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.35rem 0;
        cursor: pointer;
    }

    .filter-check input {
        width: 15px;
        height: 15px;
        accent-color: var(--accent);
        cursor: pointer;
        flex-shrink: 0;
    }

    .filter-check-label {
        font-size: 0.85rem;
        color: var(--text-dark);
        flex: 1;
        transition: color var(--transition);
    }

    .filter-check:hover .filter-check-label {
        color: var(--accent);
    }

    .filter-check-count {
        font-size: 0.68rem;
        font-weight: 600;
        color: var(--text-muted);
        background: var(--light-bg);
        border: 1px solid var(--warm);
        padding: 0.1rem 0.4rem;
        border-radius: 2px;
    }

    /* Stars in rating filter */
    .f-stars {
        display: flex;
        gap: 2px;
        color: var(--accent-color);
        font-size: 0.72rem;
    }

    .f-stars .empty {
        color: var(--warm);
    }

    /* Price inputs */
    .price-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
        margin-top: 0.65rem;
    }

    .price-input-wrap {
        position: relative;
    }

    .price-input-wrap span {
        position: absolute;
        left: 0.6rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-muted);
    }

    .price-input {
        width: 100%;
        padding: 0.5rem 0.5rem 0.5rem 1.4rem;
        border: 1px solid var(--warm);
        border-radius: 2px;
        font-size: 0.8rem;
        color: var(--text-dark);
        font-family: var(--font);
        outline: none;
        transition: border-color var(--transition);
        background: var(--light-bg);
    }

    .price-input:focus {
        border-color: var(--accent);
        background: var(--white);
    }

    .price-apply {
        margin-top: 0.65rem;
        width: 100%;
        padding: 0.55rem;
        background: var(--accent);
        color: var(--white);
        border: none;
        border-radius: 2px;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        cursor: pointer;
        font-family: var(--font);
        transition: background var(--transition);
    }

    .price-apply:hover {
        background: var(--ink-mid, #2d3748);
    }

    /* ============================================================
   MAIN PRODUCTS AREA
============================================================ */
    .shop-main {
        min-width: 0;
    }

    /* Toolbar */
    .shop-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        gap: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--warm);
    }

    .shop-count {
        font-size: 0.82rem;
        color: var(--text-muted);
    }

    .shop-count strong {
        color: var(--text-dark);
        font-weight: 700;
    }

    .toolbar-right {
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }

    /* Sort select — matches hp-btn style */
    .shop-sort {
        padding: 0.6rem 2rem 0.6rem 0.85rem;
        border: 1px solid var(--warm);
        border-radius: 2px;
        background: var(--white) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='7' viewBox='0 0 10 7'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%23718096' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") no-repeat right 0.7rem center;
        -webkit-appearance: none;
        font-size: 0.82rem;
        color: var(--text-dark);
        font-family: var(--font);
        cursor: pointer;
        outline: none;
        transition: border-color var(--transition);
    }

    .shop-sort:focus {
        border-color: var(--accent);
    }

    /* View toggle */
    .view-toggle {
        display: flex;
        border: 1px solid var(--warm);
        border-radius: 2px;
        overflow: hidden;
        background: var(--white);
    }

    .view-btn {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        cursor: pointer;
        color: var(--text-muted);
        font-size: 0.88rem;
        transition: all var(--transition);
    }

    .view-btn.active {
        background: var(--accent);
        color: var(--white);
    }

    .view-btn:not(.active):hover {
        background: var(--light-bg);
        color: var(--text-dark);
    }

    /* Mobile filter btn */
    .mobile-filter-btn {
        display: none;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 0.9rem;
        background: var(--white);
        border: 1px solid var(--warm);
        border-radius: 2px;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-dark);
        cursor: pointer;
        font-family: var(--font);
        transition: all var(--transition);
    }

    .mobile-filter-btn:hover {
        border-color: var(--accent);
        color: var(--accent);
    }

    /* ============================================================
   PRODUCT GRID — matches hp-product-card exactly
============================================================ */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }

    .products-grid.list-view {
        grid-template-columns: 1fr;
    }

    /* Card — directly inherits hp-product-card aesthetic */
    .product-card {
        background: var(--white);
        border: 1px solid var(--warm);
        border-radius: 2px;
        overflow: hidden;
        transition: all var(--transition);
        display: flex;
        flex-direction: column;
        animation: card-in 0.3s ease both;
    }

    .product-card:hover {
        border-color: var(--secondary-color);
        box-shadow: var(--shadow-lg);
        transform: translateY(-5px);
    }

    @keyframes card-in {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Stagger */
    .product-card:nth-child(1) {
        animation-delay: 0.03s;
    }

    .product-card:nth-child(2) {
        animation-delay: 0.06s;
    }

    .product-card:nth-child(3) {
        animation-delay: 0.09s;
    }

    .product-card:nth-child(4) {
        animation-delay: 0.12s;
    }

    .product-card:nth-child(5) {
        animation-delay: 0.15s;
    }

    .product-card:nth-child(6) {
        animation-delay: 0.18s;
    }

    .product-card:nth-child(7) {
        animation-delay: 0.21s;
    }

    .product-card:nth-child(8) {
        animation-delay: 0.24s;
    }

    .product-card:nth-child(9) {
        animation-delay: 0.27s;
    }

    /* Image wrap — matches hp-product-img-wrap */
    .card-img-wrap {
        position: relative;
        aspect-ratio: 4 / 3;
        overflow: hidden;
        background: var(--cream, #edf2f7);
    }

    .card-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.55s ease;
    }

    .product-card:hover .card-img-wrap img {
        transform: scale(1.06);
    }

    /* Badge on image — matches hp-product-category */
    .card-cat-badge {
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        font-size: 0.65rem;
        font-weight: 700;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        background: var(--white);
        color: var(--text-dark);
        padding: 0.28rem 0.65rem;
        border-radius: 1px;
    }

    /* Status badge (NEW / SALE) */
    .card-status-badge {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        font-size: 0.62rem;
        font-weight: 800;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        padding: 0.25rem 0.6rem;
        border-radius: 1px;
    }

    .badge--new {
        background: var(--accent);
        color: var(--white);
    }

    .badge--sale {
        background: #e53e3e;
        color: var(--white);
    }

    .badge--low {
        background: var(--accent-color);
        color: var(--accent);
    }

    .badge--out {
        background: #718096;
        color: var(--white);
    }

    /* Overlay actions — matches hp-product-actions-overlay */
    .card-overlay-actions {
        position: absolute;
        bottom: 0.75rem;
        right: 0.75rem;
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        opacity: 0;
        transform: translateX(8px);
        transition: all var(--transition);
    }

    .product-card:hover .card-overlay-actions {
        opacity: 1;
        transform: translateX(0);
    }

    /* Overlay btn — matches hp-overlay-btn */
    .overlay-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--white);
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--text-dark);
        font-size: 0.82rem;
        box-shadow: 0 2px 8px rgba(30, 58, 95, 0.15);
        transition: all var(--transition);
    }

    .overlay-btn:hover {
        background: var(--accent);
        color: var(--white);
    }

    .overlay-btn.wish-active {
        background: #fff5f5;
        color: #e53e3e;
    }

    /* Card body — matches hp-product-body */
    .card-body {
        padding: 1.1rem 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .card-cat-label {
        font-size: 0.65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-muted);
    }

    .card-name {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-dark);
        line-height: 1.3;
        margin-bottom: 0.1rem;
    }

    .card-desc {
        font-size: 0.8rem;
        color: var(--text-muted);
        line-height: 1.6;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        margin-bottom: 0.25rem;
    }

    .card-rating {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        margin-top: auto;
    }

    .card-stars {
        display: flex;
        gap: 2px;
        color: var(--accent-color);
        font-size: 0.68rem;
    }

    .card-stars .empty {
        color: var(--warm);
    }

    .card-rating-text {
        font-size: 0.7rem;
        color: var(--text-muted);
    }

    /* Card footer — matches hp-product-footer */
    .card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        padding: 0.9rem 1.25rem;
        border-top: 1px solid var(--warm);
        background: var(--light-bg);
    }

    .card-price {
        display: block;
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--accent);
        letter-spacing: -0.02em;
        line-height: 1;
    }

    .card-price-orig {
        display: block;
        font-size: 0.72rem;
        color: var(--text-muted);
        text-decoration: line-through;
        margin-top: 2px;
    }

    /* Add to cart btn — matches hp-view-btn */
    .card-cta {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: var(--accent);
        padding: 0.5rem 0.9rem;
        border: 1.5px solid var(--warm);
        border-radius: 2px;
        background: none;
        cursor: pointer;
        font-family: var(--font);
        white-space: nowrap;
        flex-shrink: 0;
        transition: all var(--transition);
    }

    .card-cta:hover {
        background: var(--accent);
        color: var(--white);
        border-color: var(--accent);
    }

    .card-cta:disabled {
        opacity: 0.45;
        cursor: not-allowed;
    }

    .card-cta:disabled:hover {
        background: none;
        color: var(--accent);
        border-color: var(--warm);
    }

    /* ---- LIST VIEW ---- */
    .products-grid.list-view .product-card {
        flex-direction: row;
        max-height: 150px;
    }

    .products-grid.list-view .card-img-wrap {
        width: 170px;
        min-width: 170px;
        aspect-ratio: unset;
        height: auto;
    }

    .products-grid.list-view .card-body {
        padding: 0.9rem 1.1rem;
    }

    .products-grid.list-view .card-footer {
        flex-direction: column;
        align-items: flex-end;
        justify-content: center;
        border-top: none;
        border-left: 1px solid var(--warm);
        min-width: 145px;
        padding: 0.9rem 1.1rem;
    }

    .products-grid.list-view .card-overlay-actions {
        display: none;
    }

    /* ---- Empty state ---- */
    .shop-empty {
        grid-column: 1 / -1;
        text-align: center;
        padding: 5rem 2rem;
    }

    .shop-empty i {
        font-size: 2.5rem;
        color: var(--warm);
        display: block;
        margin-bottom: 1rem;
    }

    .shop-empty h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.4rem;
    }

    .shop-empty p {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    /* ============================================================
   PAGINATION — matches hp-btn style
============================================================ */
    .pagination-wrap {
        margin-top: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--warm);
    }

    .page-info {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .page-info strong {
        color: var(--text-dark);
    }

    .pager {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        list-style: none;
    }

    .pager-item button {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0 0.5rem;
        background: var(--white);
        border: 1px solid var(--warm);
        border-radius: 2px;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-muted);
        cursor: pointer;
        font-family: var(--font);
        transition: all var(--transition);
    }

    .pager-item button:hover {
        border-color: var(--accent);
        color: var(--accent);
    }

    .pager-item.active button {
        background: var(--accent);
        border-color: var(--accent);
        color: var(--white);
    }

    .pager-item.disabled button {
        opacity: 0.35;
        pointer-events: none;
    }

    .pager-ellipsis {
        padding: 0 0.25rem;
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .per-page {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        font-size: 0.78rem;
        color: var(--text-muted);
    }

    .per-page select {
        padding: 0.3rem 1.4rem 0.3rem 0.55rem;
        border: 1px solid var(--warm);
        border-radius: 2px;
        background: var(--white) url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='7' viewBox='0 0 10 7'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%23718096' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") no-repeat right 0.4rem center;
        -webkit-appearance: none;
        font-size: 0.78rem;
        font-family: var(--font);
        color: var(--text-dark);
        outline: none;
        cursor: pointer;
        transition: border-color var(--transition);
    }

    .per-page select:focus {
        border-color: var(--accent);
    }

    /* ============================================================
   RESPONSIVE
============================================================ */
    @media (max-width: 1024px) {
        .shop-wrap {
            grid-template-columns: 1fr;
        }

        .shop-sidebar {
            position: static;
        }

        .products-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .mobile-filter-btn {
            display: flex;
        }
    }

    @media (max-width: 640px) {
        .shop-wrap {
            padding: 1.5rem 1.25rem 3rem;
        }

        .products-grid {
            grid-template-columns: 1fr;
        }

        .shop-hero-title {
            font-size: 1.6rem;
        }

        .pagination-wrap {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
    }
</style>

<!-- ============================================================
     PAGE HERO
============================================================ -->
<div class="shop-hero">
    <div class="shop-hero-blob shop-blob-1"></div>
    <div class="shop-hero-blob shop-blob-2"></div>

    <div class="shop-hero-inner">
        <ol class="shop-breadcrumb">
            <li><a href="index.php">Home</a></li>
            <span class="shop-breadcrumb-sep"><i class="bi bi-chevron-right"></i></span>
            <li class="active">Products</li>
        </ol>

        <div class="shop-hero-eyebrow">
            <span class="shop-eyebrow-dot"></span>
            AES Educational Supplies
        </div>

        <h1 class="shop-hero-title">
            Our <span>Product</span> Catalogue
        </h1>
        <p class="shop-hero-sub">Quality materials for students and educators across the Philippines</p>
    </div>

    <div class="shop-hero-wave">
        <svg viewBox="0 0 1440 55" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,28 C360,56 1080,0 1440,28 L1440,55 L0,55 Z" fill="#f7fafc" />
        </svg>
    </div>
</div>

<!-- ============================================================
     MAIN SHOP LAYOUT
============================================================ -->
<div class="shop-wrap">

    <!-- ===== FILTER SIDEBAR ===== -->
    <aside class="shop-sidebar" id="shopSidebar">
        <div class="sidebar-header">
            <span class="sidebar-title"><i class="bi bi-funnel-fill"></i> Filters</span>
            <button class="sidebar-clear" onclick="clearAllFilters()">Clear all</button>
        </div>

        <!-- Active tags + Category -->
        <div class="filter-block">
            <div class="active-filters" id="activeFilters"></div>

            <div class="filter-block-head" onclick="toggleFilter(this)">
                <span class="filter-block-label">Category</span>
                <i class="bi bi-chevron-down"></i>
            </div>
            <div class="filter-block-body">
                <label class="filter-check">
                    <input type="checkbox" name="category" value="books" onchange="updateFilters()">
                    <span class="filter-check-label">Books &amp; Workbooks</span>
                    <span class="filter-check-count">124</span>
                </label>
                <label class="filter-check">
                    <input type="checkbox" name="category" value="art" onchange="updateFilters()">
                    <span class="filter-check-label">Art Supplies</span>
                    <span class="filter-check-count">87</span>
                </label>
                <label class="filter-check">
                    <input type="checkbox" name="category" value="stationery" onchange="updateFilters()">
                    <span class="filter-check-label">Stationery</span>
                    <span class="filter-check-count">203</span>
                </label>
                <label class="filter-check">
                    <input type="checkbox" name="category" value="science" onchange="updateFilters()">
                    <span class="filter-check-label">Science &amp; Lab</span>
                    <span class="filter-check-count">56</span>
                </label>
                <label class="filter-check">
                    <input type="checkbox" name="category" value="sports" onchange="updateFilters()">
                    <span class="filter-check-label">Sports &amp; PE</span>
                    <span class="filter-check-count">43</span>
                </label>
                <label class="filter-check">
                    <input type="checkbox" name="category" value="tech" onchange="updateFilters()">
                    <span class="filter-check-label">Tech &amp; Devices</span>
                    <span class="filter-check-count">31</span>
                </label>
            </div>
        </div>

        <!-- Grade Level -->
        <div class="filter-block">
            <div class="filter-block-head" onclick="toggleFilter(this)">
                <span class="filter-block-label">Grade Level</span>
                <i class="bi bi-chevron-down"></i>
            </div>
            <div class="filter-block-body">
                <label class="filter-check">
                    <input type="checkbox" name="grade" value="kinder" onchange="updateFilters()">
                    <span class="filter-check-label">Kindergarten</span>
                    <span class="filter-check-count">48</span>
                </label>
                <label class="filter-check">
                    <input type="checkbox" name="grade" value="elementary" onchange="updateFilters()">
                    <span class="filter-check-label">Elementary (1–6)</span>
                    <span class="filter-check-count">215</span>
                </label>
                <label class="filter-check">
                    <input type="checkbox" name="grade" value="jhs" onchange="updateFilters()">
                    <span class="filter-check-label">Junior High (7–10)</span>
                    <span class="filter-check-count">167</span>
                </label>
                <label class="filter-check">
                    <input type="checkbox" name="grade" value="shs" onchange="updateFilters()">
                    <span class="filter-check-label">Senior High (11–12)</span>
                    <span class="filter-check-count">89</span>
                </label>
                <label class="filter-check">
                    <input type="checkbox" name="grade" value="college" onchange="updateFilters()">
                    <span class="filter-check-label">College</span>
                    <span class="filter-check-count">72</span>
                </label>
            </div>
        </div>

        <!-- Price Range -->
        <div class="filter-block">
            <div class="filter-block-head" onclick="toggleFilter(this)">
                <span class="filter-block-label">Price Range</span>
                <i class="bi bi-chevron-down"></i>
            </div>
            <div class="filter-block-body">
                <label class="filter-check"><input type="radio" name="price_range" value="under50" onchange="updateFilters()"><span class="filter-check-label">Under ₱50</span></label>
                <label class="filter-check"><input type="radio" name="price_range" value="50-150" onchange="updateFilters()"><span class="filter-check-label">₱50 – ₱150</span></label>
                <label class="filter-check"><input type="radio" name="price_range" value="150-500" onchange="updateFilters()"><span class="filter-check-label">₱150 – ₱500</span></label>
                <label class="filter-check"><input type="radio" name="price_range" value="500-1000" onchange="updateFilters()"><span class="filter-check-label">₱500 – ₱1,000</span></label>
                <label class="filter-check"><input type="radio" name="price_range" value="over1000" onchange="updateFilters()"><span class="filter-check-label">Over ₱1,000</span></label>
                <div class="price-inputs">
                    <div class="price-input-wrap"><span>₱</span><input type="number" class="price-input" placeholder="Min" id="priceMin"></div>
                    <div class="price-input-wrap"><span>₱</span><input type="number" class="price-input" placeholder="Max" id="priceMax"></div>
                </div>
                <button class="price-apply" onclick="applyPriceRange()">Apply Range</button>
            </div>
        </div>

        <!-- Rating -->
        <div class="filter-block">
            <div class="filter-block-head" onclick="toggleFilter(this)">
                <span class="filter-block-label">Rating</span>
                <i class="bi bi-chevron-down"></i>
            </div>
            <div class="filter-block-body">
                <label class="filter-check">
                    <input type="checkbox" name="rating" value="5" onchange="updateFilters()">
                    <div class="f-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                    <span class="filter-check-label" style="font-size:0.75rem;">Only</span>
                </label>
                <label class="filter-check">
                    <input type="checkbox" name="rating" value="4" onchange="updateFilters()">
                    <div class="f-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star empty"></i></div>
                    <span class="filter-check-label" style="font-size:0.75rem;">&amp; up</span>
                </label>
                <label class="filter-check">
                    <input type="checkbox" name="rating" value="3" onchange="updateFilters()">
                    <div class="f-stars"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star empty"></i><i class="bi bi-star empty"></i></div>
                    <span class="filter-check-label" style="font-size:0.75rem;">&amp; up</span>
                </label>
            </div>
        </div>

        <!-- Availability -->
        <div class="filter-block">
            <div class="filter-block-head" onclick="toggleFilter(this)">
                <span class="filter-block-label">Availability</span>
                <i class="bi bi-chevron-down"></i>
            </div>
            <div class="filter-block-body">
                <label class="filter-check"><input type="checkbox" name="availability" value="in_stock" onchange="updateFilters()"><span class="filter-check-label">In Stock</span></label>
                <label class="filter-check"><input type="checkbox" name="availability" value="new" onchange="updateFilters()"><span class="filter-check-label">New Arrivals</span></label>
                <label class="filter-check"><input type="checkbox" name="availability" value="sale" onchange="updateFilters()"><span class="filter-check-label">On Sale</span></label>
            </div>
        </div>
    </aside>

    <!-- ===== PRODUCTS AREA ===== -->
    <main class="shop-main">

        <!-- Toolbar -->
        <div class="shop-toolbar">
            <p class="shop-count">
                Showing <strong id="showingFrom">1</strong>–<strong id="showingTo">12</strong>
                of <strong id="totalCount">24</strong> products
            </p>
            <div class="toolbar-right">
                <button class="mobile-filter-btn" onclick="toggleMobileFilters()">
                    <i class="bi bi-funnel"></i> Filters
                </button>
                <select class="shop-sort" id="sortSelect" onchange="sortProducts()">
                    <option value="featured">Featured</option>
                    <option value="newest">Newest First</option>
                    <option value="price-asc">Price: Low → High</option>
                    <option value="price-desc">Price: High → Low</option>
                    <option value="rating">Top Rated</option>
                    <option value="name">Name A–Z</option>
                </select>
                <div class="view-toggle">
                    <button class="view-btn active" id="gridViewBtn" onclick="setView('grid')" title="Grid">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </button>
                    <button class="view-btn" id="listViewBtn" onclick="setView('list')" title="List">
                        <i class="bi bi-list-ul"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="products-grid" id="productsGrid"></div>

        <!-- Pagination -->
        <div class="pagination-wrap">
            <div class="per-page">
                Show
                <select onchange="changePerPage(this.value)">
                    <option value="12" selected>12</option>
                    <option value="24">24</option>
                    <option value="48">48</option>
                </select>
                per page
            </div>
            <ul class="pager" id="pager"></ul>
            <div class="page-info">
                Page <strong id="currentPageLabel">1</strong> of <strong id="totalPagesLabel">2</strong>
            </div>
        </div>

    </main>
</div>

<script>
    // ============================================================
    // MOCK DATA
    // ============================================================
    const PRODUCTS = [{
            id: 1,
            name: 'Intermediate Mathematics Workbook',
            cat: 'Books & Workbooks',
            price: 185,
            orig: 220,
            rating: 4.8,
            reviews: 124,
            badge: 'sale',
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?w=400&h=300&fit=crop',
            desc: 'Comprehensive workbook for Grades 7–8 with step-by-step solutions.'
        },
        {
            id: 2,
            name: 'Mongol Pencils Set (12 pcs)',
            cat: 'Stationery',
            price: 45,
            orig: null,
            rating: 4.6,
            reviews: 88,
            badge: 'new',
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?w=400&h=300&fit=crop',
            desc: 'Classic pre-sharpened pencils, ideal for everyday school use.'
        },
        {
            id: 3,
            name: 'Watercolor Paint Set 24 Colors',
            cat: 'Art Supplies',
            price: 320,
            orig: 399,
            rating: 4.9,
            reviews: 201,
            badge: 'sale',
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1541961017774-22349e4a1262?w=400&h=300&fit=crop',
            desc: 'Vibrant, non-toxic watercolors perfect for student art projects.'
        },
        {
            id: 4,
            name: 'Science Lab Goggles',
            cat: 'Science & Lab',
            price: 125,
            orig: null,
            rating: 4.5,
            reviews: 43,
            badge: null,
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?w=400&h=300&fit=crop',
            desc: 'ANSI-certified safety goggles for chemistry and biology labs.'
        },
        {
            id: 5,
            name: 'Composition Notebook (5 pack)',
            cat: 'Stationery',
            price: 98,
            orig: null,
            rating: 4.3,
            reviews: 156,
            badge: null,
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1544816155-12df9643f363?w=400&h=300&fit=crop',
            desc: 'Wide-ruled composition notebooks, 100 pages each.'
        },
        {
            id: 6,
            name: 'Colored Markers 20-set',
            cat: 'Art Supplies',
            price: 210,
            orig: 260,
            rating: 4.7,
            reviews: 77,
            badge: 'sale',
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1456735190827-d1262f71b8a3?w=400&h=300&fit=crop',
            desc: 'Dual-tip markers with fine and broad ends for art and labeling.'
        },
        {
            id: 7,
            name: 'English Reading Comprehension Gr.5',
            cat: 'Books & Workbooks',
            price: 145,
            orig: null,
            rating: 4.4,
            reviews: 62,
            badge: 'new',
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&h=300&fit=crop',
            desc: 'Engaging reading passages with comprehension exercises.'
        },
        {
            id: 8,
            name: 'Scientific Calculator FX-991',
            cat: 'Tech & Devices',
            price: 895,
            orig: 1050,
            rating: 4.9,
            reviews: 318,
            badge: 'sale',
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1611532736597-de2d4265fba3?w=400&h=300&fit=crop',
            desc: 'Advanced scientific calculator with 417 functions.'
        },
        {
            id: 9,
            name: 'Badminton Shuttlecocks (12 pcs)',
            cat: 'Sports & PE',
            price: 180,
            orig: null,
            rating: 4.2,
            reviews: 29,
            badge: null,
            stock: 'low',
            img: 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=400&h=300&fit=crop',
            desc: 'Feather shuttlecocks for school PE and recreational play.'
        },
        {
            id: 10,
            name: 'Globe World Map (30cm)',
            cat: 'Science & Lab',
            price: 650,
            orig: null,
            rating: 4.6,
            reviews: 54,
            badge: null,
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=300&fit=crop',
            desc: 'Physical and political world globe with country labels.'
        },
        {
            id: 11,
            name: 'Ruler Set (30cm + 15cm)',
            cat: 'Stationery',
            price: 35,
            orig: null,
            rating: 4.1,
            reviews: 91,
            badge: null,
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=300&fit=crop',
            desc: 'Transparent plastic rulers with metric and imperial markings.'
        },
        {
            id: 12,
            name: 'Acrylic Paint Set 18 Colors',
            cat: 'Art Supplies',
            price: 485,
            orig: 580,
            rating: 4.8,
            reviews: 143,
            badge: 'sale',
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=400&h=300&fit=crop',
            desc: 'Heavy-body acrylic paints for canvas, paper, and mixed media.'
        },
        {
            id: 13,
            name: 'Science Activity Kit Gr.3',
            cat: 'Science & Lab',
            price: 299,
            orig: null,
            rating: 4.5,
            reviews: 38,
            badge: 'new',
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1628595351029-c2bf17511435?w=400&h=300&fit=crop',
            desc: 'Hands-on science experiments for young learners.'
        },
        {
            id: 14,
            name: 'Highlighter Set 6 Colors',
            cat: 'Stationery',
            price: 55,
            orig: null,
            rating: 4.4,
            reviews: 207,
            badge: null,
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400&h=300&fit=crop',
            desc: 'Chisel-tip fluorescent highlighters for notes and studying.'
        },
        {
            id: 15,
            name: 'Trigonometry & Geometry Textbook',
            cat: 'Books & Workbooks',
            price: 320,
            orig: null,
            rating: 4.7,
            reviews: 81,
            badge: null,
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1509228468518-180dd4864904?w=400&h=300&fit=crop',
            desc: 'Complete SHS-aligned trigonometry and geometry textbook.'
        },
        {
            id: 16,
            name: 'Badminton Racket Pair',
            cat: 'Sports & PE',
            price: 750,
            orig: 900,
            rating: 4.3,
            reviews: 17,
            badge: 'sale',
            stock: 'out',
            img: 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=400&h=300&fit=crop',
            desc: 'Lightweight aluminum-frame rackets for school PE programs.'
        },
        {
            id: 17,
            name: 'Sketch Pad A4 (50 sheets)',
            cat: 'Art Supplies',
            price: 95,
            orig: null,
            rating: 4.6,
            reviews: 112,
            badge: null,
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=400&h=300&fit=crop',
            desc: '160gsm acid-free sketch paper for pencil and charcoal work.'
        },
        {
            id: 18,
            name: 'USB Flash Drive 32GB',
            cat: 'Tech & Devices',
            price: 285,
            orig: 350,
            rating: 4.5,
            reviews: 244,
            badge: 'sale',
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1618044619888-009e412ff12a?w=400&h=300&fit=crop',
            desc: 'Fast read/write speeds, durable casing, compatible with all OS.'
        },
        {
            id: 19,
            name: 'Scissor & Glue Combo Pack',
            cat: 'Stationery',
            price: 75,
            orig: null,
            rating: 4.2,
            reviews: 66,
            badge: null,
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1565193566173-7a0ee3dbe261?w=400&h=300&fit=crop',
            desc: 'Stainless steel scissors with all-purpose glue stick.'
        },
        {
            id: 20,
            name: 'Filipino Literature Anthology',
            cat: 'Books & Workbooks',
            price: 195,
            orig: null,
            rating: 4.8,
            reviews: 93,
            badge: 'new',
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1474932430478-1132ced98e35?w=400&h=300&fit=crop',
            desc: 'Curated Filipino short stories, poems, and essays.'
        },
        {
            id: 21,
            name: 'Eraser Set (Assorted, 10 pcs)',
            cat: 'Stationery',
            price: 28,
            orig: null,
            rating: 4.0,
            reviews: 175,
            badge: null,
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1581803118522-7b72a50f7e9f?w=400&h=300&fit=crop',
            desc: 'Soft vinyl erasers that erase cleanly without smudging.'
        },
        {
            id: 22,
            name: 'Oil Pastels 24-set',
            cat: 'Art Supplies',
            price: 135,
            orig: 160,
            rating: 4.7,
            reviews: 88,
            badge: 'sale',
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=400&h=300&fit=crop',
            desc: 'Rich, blendable oil pastels for vibrant illustrations.'
        },
        {
            id: 23,
            name: 'School Backpack 20L Navy',
            cat: 'Stationery',
            price: 520,
            orig: null,
            rating: 4.5,
            reviews: 311,
            badge: null,
            stock: 'low',
            img: 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=300&fit=crop',
            desc: 'Ergonomic school bag with laptop compartment and padded straps.'
        },
        {
            id: 24,
            name: 'Anatomy Poster Set (6 pcs)',
            cat: 'Science & Lab',
            price: 240,
            orig: null,
            rating: 4.6,
            reviews: 27,
            badge: 'new',
            stock: 'in',
            img: 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=400&h=300&fit=crop',
            desc: 'Full-color anatomical charts for biology classrooms.'
        },
    ];

    // ============================================================
    // STATE
    // ============================================================
    let state = {
        all: [...PRODUCTS],
        filtered: [...PRODUCTS],
        page: 1,
        perPage: 12,
        view: 'grid',
        sort: 'featured',
        cart: 0,
        wishlist: new Set(),
    };

    // ============================================================
    // RENDER
    // ============================================================
    function renderProducts() {
        const grid = document.getElementById('productsGrid');
        const start = (state.page - 1) * state.perPage;
        const slice = state.filtered.slice(start, start + state.perPage);

        grid.className = 'products-grid' + (state.view === 'list' ? ' list-view' : '');

        if (!slice.length) {
            grid.innerHTML = `<div class="shop-empty"><i class="bi bi-search"></i><h3>No products found</h3><p>Try adjusting your filters or search terms.</p></div>`;
            renderPager();
            return;
        }

        grid.innerHTML = slice.map((p, i) => {
            const badgeHtml = p.badge ?
                `<span class="card-status-badge badge--${p.badge}">${p.badge === 'new' ? 'New' : p.badge === 'sale' ? 'Sale' : ''}</span>` :
                (p.stock === 'low' ? `<span class="card-status-badge badge--low">Low Stock</span>` : '');

            const origHtml = p.orig ? `<span class="card-price-orig">₱${p.orig.toLocaleString()}</span>` : '';

            const stars = Array.from({
                    length: 5
                }, (_, s) =>
                `<i class="bi bi-star-fill${s >= Math.round(p.rating) ? ' empty' : ''}"></i>`
            ).join('');

            const isOut = p.stock === 'out';
            const isWished = state.wishlist.has(p.id);

            return `
        <div class="product-card" style="animation-delay:${i*0.04}s">
            <div class="card-img-wrap">
                <img src="${p.img}" alt="${p.name}" loading="lazy"
                    onerror="this.src='https://via.placeholder.com/400x300/edf2f7/718096?text=AES'">
                <span class="card-cat-badge">${p.cat}</span>
                ${badgeHtml}
                <div class="card-overlay-actions">
                    <button class="overlay-btn ${isWished ? 'wish-active' : ''}"
                        onclick="toggleWish(${p.id},this)" title="Wishlist">
                        <i class="bi bi-heart${isWished ? '-fill' : ''}"></i>
                    </button>
                    <button class="overlay-btn" title="Quick view" onclick="quickView(${p.id})">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <span class="card-cat-label">${p.cat}</span>
                <div class="card-name">${p.name}</div>
                <div class="card-desc">${p.desc}</div>
                <div class="card-rating">
                    <div class="card-stars">${stars}</div>
                    <span class="card-rating-text">${p.rating} (${p.reviews})</span>
                </div>
            </div>
            <div class="card-footer">
                <div>
                    <span class="card-price">₱${p.price.toLocaleString()}</span>
                    ${origHtml}
                </div>
                <button class="card-cta" onclick="addToCart(${p.id},this)" ${isOut ? 'disabled' : ''}>
                    <i class="bi bi-${isOut ? 'x-circle' : 'bag-plus'}"></i>
                    ${isOut ? 'Out of Stock' : 'Add to Cart'}
                </button>
            </div>
        </div>`;
        }).join('');

        document.getElementById('showingFrom').textContent = start + 1;
        document.getElementById('showingTo').textContent = Math.min(start + state.perPage, state.filtered.length);
        document.getElementById('totalCount').textContent = state.filtered.length;
        renderPager();
    }

    // ============================================================
    // PAGER
    // ============================================================
    function renderPager() {
        const total = Math.ceil(state.filtered.length / state.perPage);
        const cur = state.page;
        document.getElementById('currentPageLabel').textContent = cur;
        document.getElementById('totalPagesLabel').textContent = total || 1;

        const pager = document.getElementById('pager');
        const range = new Set([1, total]);
        for (let i = Math.max(2, cur - 2); i <= Math.min(total - 1, cur + 2); i++) range.add(i);
        const pages = [...range].sort((a, b) => a - b);

        let html = `<li class="pager-item ${cur===1?'disabled':''}"><button onclick="goPage(${cur-1})"><i class="bi bi-chevron-left"></i></button></li>`;
        let prev = null;
        pages.forEach(p => {
            if (prev !== null && p - prev > 1) html += `<li class="pager-ellipsis">…</li>`;
            html += `<li class="pager-item ${p===cur?'active':''}"><button onclick="goPage(${p})">${p}</button></li>`;
            prev = p;
        });
        html += `<li class="pager-item ${cur===total||total===0?'disabled':''}"><button onclick="goPage(${cur+1})"><i class="bi bi-chevron-right"></i></button></li>`;
        pager.innerHTML = html;
    }

    function goPage(p) {
        const total = Math.ceil(state.filtered.length / state.perPage);
        if (p < 1 || p > total) return;
        state.page = p;
        renderProducts();
        window.scrollTo({
            top: document.querySelector('.shop-wrap').offsetTop - 20,
            behavior: 'smooth'
        });
    }

    function changePerPage(v) {
        state.perPage = +v;
        state.page = 1;
        renderProducts();
    }

    // ============================================================
    // FILTERS & SORT
    // ============================================================
    function applyFiltersAndSort() {
        let r = [...state.all];

        const cats = [...document.querySelectorAll('input[name="category"]:checked')].map(e => e.value);
        if (cats.length) {
            const map = {
                books: 'Books & Workbooks',
                art: 'Art Supplies',
                stationery: 'Stationery',
                science: 'Science & Lab',
                sports: 'Sports & PE',
                tech: 'Tech & Devices'
            };
            r = r.filter(p => cats.some(c => p.cat === map[c]));
        }

        const pr = document.querySelector('input[name="price_range"]:checked');
        if (pr) {
            const v = pr.value;
            if (v === 'under50') r = r.filter(p => p.price < 50);
            if (v === '50-150') r = r.filter(p => p.price >= 50 && p.price <= 150);
            if (v === '150-500') r = r.filter(p => p.price >= 150 && p.price <= 500);
            if (v === '500-1000') r = r.filter(p => p.price >= 500 && p.price <= 1000);
            if (v === 'over1000') r = r.filter(p => p.price > 1000);
        }

        const mn = parseFloat(document.getElementById('priceMin').value);
        const mx = parseFloat(document.getElementById('priceMax').value);
        if (!isNaN(mn)) r = r.filter(p => p.price >= mn);
        if (!isNaN(mx)) r = r.filter(p => p.price <= mx);

        const rats = [...document.querySelectorAll('input[name="rating"]:checked')].map(e => +e.value);
        if (rats.length) {
            const min = Math.min(...rats);
            r = r.filter(p => p.rating >= min);
        }

        const avs = [...document.querySelectorAll('input[name="availability"]:checked')].map(e => e.value);
        if (avs.length) r = r.filter(p => {
            if (avs.includes('in_stock') && p.stock !== 'out') return true;
            if (avs.includes('new') && p.badge === 'new') return true;
            if (avs.includes('sale') && p.badge === 'sale') return true;
            return false;
        });

        const s = state.sort;
        if (s === 'price-asc') r.sort((a, b) => a.price - b.price);
        if (s === 'price-desc') r.sort((a, b) => b.price - a.price);
        if (s === 'rating') r.sort((a, b) => b.rating - a.rating);
        if (s === 'newest') r.sort((a, b) => b.id - a.id);
        if (s === 'name') r.sort((a, b) => a.name.localeCompare(b.name));

        state.filtered = r;
        state.page = 1;
        renderProducts();
        renderActiveTags();
    }

    function updateFilters() {
        applyFiltersAndSort();
    }

    function sortProducts() {
        state.sort = document.getElementById('sortSelect').value;
        applyFiltersAndSort();
    }

    function applyPriceRange() {
        applyFiltersAndSort();
    }

    function clearAllFilters() {
        document.querySelectorAll('input[type="checkbox"],input[type="radio"]').forEach(e => e.checked = false);
        document.getElementById('priceMin').value = '';
        document.getElementById('priceMax').value = '';
        applyFiltersAndSort();
    }

    // ============================================================
    // ACTIVE TAGS
    // ============================================================
    function renderActiveTags() {
        const c = document.getElementById('activeFilters');
        const tags = [];

        document.querySelectorAll('input[name="category"]:checked').forEach(el =>
            tags.push({
                label: el.closest('.filter-check').querySelector('.filter-check-label').textContent,
                clear: () => {
                    el.checked = false;
                    applyFiltersAndSort();
                }
            })
        );

        const pr = document.querySelector('input[name="price_range"]:checked');
        if (pr) tags.push({
            label: pr.closest('.filter-check').querySelector('.filter-check-label').textContent,
            clear: () => {
                pr.checked = false;
                applyFiltersAndSort();
            }
        });

        document.querySelectorAll('input[name="rating"]:checked').forEach(el =>
            tags.push({
                label: `${el.value}★+`,
                clear: () => {
                    el.checked = false;
                    applyFiltersAndSort();
                }
            })
        );

        document.querySelectorAll('input[name="availability"]:checked').forEach(el =>
            tags.push({
                label: el.closest('.filter-check').querySelector('.filter-check-label').textContent,
                clear: () => {
                    el.checked = false;
                    applyFiltersAndSort();
                }
            })
        );

        if (!tags.length) {
            c.innerHTML = '';
            return;
        }
        c.innerHTML = tags.map((t, i) => `<span class="filter-tag">${t.label}<button onclick="clearTag(${i})">✕</button></span>`).join('');
        window._tagClears = tags.map(t => t.clear);
    }

    window.clearTag = i => window._tagClears[i]();

    // ============================================================
    // UI HELPERS
    // ============================================================
    function toggleFilter(head) {
        head.nextElementSibling.classList.toggle('hidden');
        head.classList.toggle('collapsed');
    }

    function setView(v) {
        state.view = v;
        document.getElementById('gridViewBtn').classList.toggle('active', v === 'grid');
        document.getElementById('listViewBtn').classList.toggle('active', v === 'list');
        renderProducts();
    }

    function addToCart(id, btn) {
        state.cart++;
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check-circle-fill"></i> Added!';
        btn.style.background = '#1e3a5f';
        btn.style.color = '#fff';
        btn.style.borderColor = '#1e3a5f';
        setTimeout(() => {
            btn.innerHTML = orig;
            btn.style = '';
        }, 1500);
    }

    function toggleWish(id, btn) {
        if (state.wishlist.has(id)) {
            state.wishlist.delete(id);
            btn.classList.remove('wish-active');
            btn.innerHTML = '<i class="bi bi-heart"></i>';
        } else {
            state.wishlist.add(id);
            btn.classList.add('wish-active');
            btn.innerHTML = '<i class="bi bi-heart-fill"></i>';
        }
    }

    function quickView(id) {
        const p = state.all.find(x => x.id === id);
        alert(`${p.name}\n₱${p.price.toLocaleString()}\n\n${p.desc}`);
    }

    function toggleMobileFilters() {
        const s = document.getElementById('shopSidebar');
        s.style.display = s.style.display === 'none' ? 'block' : 'none';
    }

    // ============================================================
    // INIT
    // ============================================================
    renderProducts();
</script>

<?php include 'includes/footer.php'; ?>