<?php
$current_cat_id = $category_id ?? $_GET['id'] ?? $_GET['category'] ?? 0;
$current_search = $search ?? $_GET['search'] ?? '';
?>
<div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
    <!-- Tiêu đề Bộ Lọc -->
    <h5 class="fw-bold mb-3 d-flex align-items-center gap-2 text-dark">
        <span>🔍</span> Bộ Lọc
    </h5>
    <hr class="my-2 text-muted opacity-25">

    <!-- Ô tìm kiếm -->
    <form action="category.php" method="GET" class="my-3">
        <?php if (!empty($current_cat_id)): ?>
            <input type="hidden" name="id" value="<?= $current_cat_id ?>">
        <?php endif; ?>
        <div class="input-group">
            <input type="text" name="search" class="form-control rounded-start-3" placeholder="Tìm kiếm..." value="<?= htmlspecialchars($current_search) ?>">
            <button class="btn btn-success rounded-end-3 px-3" type="submit">
                🔍
            </button>
        </div>
    </form>

    <!-- Tiêu đề Danh Mục -->
    <h6 class="fw-bold text-dark my-3 d-flex align-items-center gap-2">
        📁 Danh Mục
    </h6>
    
    <!-- Danh sách Danh Mục (Lấy động từ Database) -->
    <ul class="list-unstyled mb-0">
        <li class="mb-2">
            <a href="category.php" class="text-decoration-none d-block py-1 px-2 rounded-2 <?= empty($current_cat_id) ? 'fw-bold text-primary bg-light' : 'text-dark' ?>">
                📌 Tất Cả
            </a>
        </li>
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $cat): ?>
                <li class="mb-2">
                    <a href="category.php?id=<?= $cat['id'] ?>" class="text-decoration-none d-block py-1 px-2 rounded-2 <?= ($current_cat_id == $cat['id']) ? 'fw-bold text-primary bg-light' : 'text-dark' ?>">
                        📌 <?= htmlspecialchars($cat['category_name']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

<!-- Mẹo -->
<div class="card border-0 shadow-sm rounded-4 p-3 bg-warning text-dark">
    <h6 class="fw-bold mb-2 d-flex align-items-center gap-2">
        <span>💡</span> Mẹo
    </h6>
    <p class="small mb-0 text-dark opacity-75">
        Sử dụng bộ lọc bên cạnh để tìm kiếm bài viết theo danh mục hoặc từ khóa.
    </p>
</div>