<div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
    <!-- Tiêu đề Bộ Lọc -->
    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2 text-dark">
        <span>🔍</span> Bộ Lọc
    </h5>
    <hr class="my-2 text-muted opacity-25">

    <!-- Ô tìm kiếm -->
    <form action="index.php" method="GET" class="my-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control rounded-start-3" placeholder="Tìm kiếm..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            <button class="btn btn-success rounded-end-3 px-3" type="submit">
                🔍
            </button>
        </div>
    </form>

    <!-- Tiêu đề Danh Mục -->
    <h6 class="fw-bold text-dark my-3 d-flex align-items-center gap-2">
        📁 Danh Mục
    </h6>
    
    <!-- Danh sách Danh Mục -->
    <ul class="list-unstyled mb-0">
        <li class="mb-2">
            <a href="index.php" class="text-decoration-none d-block py-1 px-2 rounded-2 <?= !isset($_GET['category']) ? 'fw-bold text-primary bg-light' : 'text-dark' ?>">
                📌 Tất Cả
            </a>
        </li>
        <li class="mb-2">
            <a href="index.php?category=1" class="text-decoration-none d-block py-1 px-2 rounded-2 text-dark">
                📰 Du Lịch
            </a>
        </li>
        <li class="mb-2">
            <a href="index.php?category=2" class="text-decoration-none d-block py-1 px-2 rounded-2 text-dark">
                🎭 Văn Hóa
            </a>
        </li>
        <li class="mb-2">
            <a href="index.php?category=3" class="text-decoration-none d-block py-1 px-2 rounded-2 text-dark">
                🍜 Ẩm Thực
            </a>
        </li>
        <li class="mb-2">
            <a href="index.php?category=4" class="text-decoration-none d-block py-1 px-2 rounded-2 text-dark">
                🎉 Sự Kiện
            </a>
        </li>
    </ul>
</div>