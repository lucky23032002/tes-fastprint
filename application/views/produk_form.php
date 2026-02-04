<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Produk - Admin Dashboard</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; overflow-x: hidden; }
        
        /* SIDEBAR */
        .sidebar { 
            width: 260px; height: 100vh; background-color: #1e293b; color: #94a3b8;
            position: fixed; top: 0; left: 0; display: flex; flex-direction: column; 
            transition: all 0.3s; z-index: 1000;
        }
        .sidebar-brand { padding: 1.5rem; color: white; font-weight: 700; font-size: 1.2rem; display: flex; align-items: center; border-bottom: 1px solid #334155; }
        .nav-link { color: #cbd5e1; padding: 0.8rem 1.5rem; display: flex; align-items: center; text-decoration: none; font-weight: 500; border-left: 3px solid transparent; }
        .nav-link:hover, .nav-link.active { background-color: #0f172a; color: #60a5fa; border-left-color: #60a5fa; }
        .nav-link i { width: 24px; margin-right: 10px; }

        /* MAIN CONTENT */
        .main-content { margin-left: 260px; padding: 2rem; min-height: 100vh; transition: all 0.3s; }

        /* FORM CARD */
        .form-card { background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0; overflow: hidden; }
        .form-header { background-color: #fff; border-bottom: 1px solid #e2e8f0; padding: 1.5rem; }
        .form-label { font-weight: 600; color: #334155; font-size: 0.9rem; margin-bottom: 0.5rem; }
        .form-control, .form-select { border-radius: 8px; padding: 0.7rem 1rem; border: 1px solid #cbd5e1; font-size: 0.95rem; }
        .form-control:focus, .form-select:focus { border-color: #60a5fa; box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.1); }
        .input-group-text { background-color: #f8fafc; border-color: #cbd5e1; color: #64748b; }
        .btn-simpan { background-color: #2563eb; border: none; padding: 0.8rem 1.5rem; font-weight: 500; }
        .btn-simpan:hover { background-color: #1d4ed8; }

        /* RESPONSIVE MOBILE */
        .menu-toggle { display: none; font-size: 1.5rem; cursor: pointer; color: #333; margin-right: 1rem; }
        .overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999; }
        .overlay.active { display: block; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 1rem; }
            .menu-toggle { display: block; }
            .sidebar-brand i.fa-xmark { display: block; cursor: pointer; }
        }
    </style>
</head>
<body>

    <div class="overlay" id="overlay" onclick="toggleMenu()"></div>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand d-flex justify-content-between">
            <span><i class="fa-solid fa-layer-group me-2 text-primary"></i> FastPrint<span class="text-primary">Admin</span></span>
            <i class="fa-solid fa-xmark d-md-none" onclick="toggleMenu()"></i>
        </div>
        <nav class="mt-4 d-flex flex-column flex-grow-1">
            <a href="<?= site_url('produk') ?>" class="nav-link active"><i class="fa-solid fa-box"></i> Produk</a>
            <a href="#" class="nav-link"><i class="fa-solid fa-chart-pie"></i> Kategori</a>
            <a href="#" class="nav-link"><i class="fa-solid fa-tags"></i> Status</a>
        </nav>
    </div>

    <div class="main-content">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-bars menu-toggle" onclick="toggleMenu()"></i>
                <div>
                    <nav aria-label="breadcrumb" class="d-none d-md-block">
                        <ol class="breadcrumb mb-1">
                            <li class="breadcrumb-item"><a href="<?= site_url('produk') ?>" class="text-decoration-none text-muted">Produk</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?= isset($produk) ? 'Edit' : 'Tambah' ?></li>
                        </ol>
                    </nav>
                    <h3 class="fw-bold text-dark mb-0 fs-4"><?= isset($produk) ? 'Edit Produk' : 'Produk Baru' ?></h3>
                </div>
            </div>
            
            <div class="d-flex align-items-center">
                <div class="text-end me-3 d-none d-md-block">
                    <div class="fw-bold small">Administrator</div>
                    <div class="text-muted" style="font-size: 0.7rem;">Head Office</div>
                </div>
                <img src="https://ui-avatars.com/api/?name=Admin+FastPrint&background=0D8ABC&color=fff" class="rounded-circle" width="40">
            </div>
        </div>

        <?php if(validation_errors()): ?>
            <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4 d-flex align-items-center">
                <i class="fa-solid fa-circle-exclamation fa-lg me-3"></i>
                <div>
                    <h6 class="fw-bold mb-1">Gagal Menyimpan!</h6>
                    <div class="small"><?= validation_errors() ?></div>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-card">
            <div class="form-header d-flex justify-content-between align-items-center">
                <div class="text-muted small"><i class="fa-solid fa-info-circle me-1"></i> Silakan isi data produk dengan lengkap.</div>
            </div>
            
            <div class="p-4 p-md-5">
                <form action="<?= isset($produk) ? site_url('produk/edit/'.$produk->id_produk) : site_url('produk/tambah') ?>" method="post">
                    
                    <?php if(isset($produk)): ?>
                        <input type="hidden" name="id_produk" value="<?= $produk->id_produk ?>">
                    <?php endif; ?>

                    <div class="row g-4">
                        <div class="col-12 col-lg-8">
                            <div class="mb-4">
                                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-box-open"></i></span>
                                    <input type="text" name="nama_produk" class="form-control" 
                                           placeholder="Masukkan nama produk..."
                                           value="<?= isset($produk) ? $produk->nama_produk : set_value('nama_produk') ?>" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Harga <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="harga" class="form-control" 
                                           placeholder="0"
                                           value="<?= isset($produk) ? $produk->harga : set_value('harga') ?>" required>
                                </div>
                                <div class="form-text">Hanya masukkan angka tanpa titik/koma.</div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-4">
                            <div class="p-3 bg-light rounded-3 border border-light">
                                <h6 class="fw-bold mb-3 text-dark">Atribut Produk</h6>
                                
                                <div class="mb-3">
                                    <label class="form-label small">Kategori</label>
                                    <select name="kategori_id" class="form-select">
                                        <?php foreach($kategori as $k): ?>
                                            <option value="<?= $k->id_kategori ?>" 
                                                <?= (isset($produk) && $produk->kategori_id == $k->id_kategori) ? 'selected' : '' ?>>
                                                <?= $k->nama_kategori ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small">Status Penjualan</label>
                                    <select name="status_id" class="form-select">
                                        <?php foreach($status as $s): ?>
                                            <option value="<?= $s->id_status ?>" 
                                                <?= (isset($produk) && $produk->status_id == $s->id_status) ? 'selected' : '' ?>>
                                                <?= $s->nama_status ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-4 border-top">
                        <a href="<?= site_url('produk') ?>" class="btn btn-light px-4 border">Batal</a>
                        <button type="submit" class="btn btn-primary btn-simpan px-4 text-white shadow-sm">
                            <i class="fa-solid fa-save me-2"></i> Simpan Data
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <footer class="mt-5 text-center text-muted small pb-4">
            &copy; 2026 FastPrint.
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleMenu() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        }
    </script>
</body>
</html>