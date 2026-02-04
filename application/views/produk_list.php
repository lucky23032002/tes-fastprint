<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FastPrint</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; overflow-x: hidden; }
        
        /* SIDEBAR STYLE */
        .sidebar { 
            width: 260px; height: 100vh; background-color: #1e293b; color: #94a3b8; 
            position: fixed; top: 0; left: 0; display: flex; flex-direction: column; 
            transition: all 0.3s; z-index: 1000; 
        }
        .sidebar-brand { padding: 1.5rem; color: white; font-weight: 700; font-size: 1.2rem; display: flex; align-items: center; border-bottom: 1px solid #334155; }
        .nav-link { color: #cbd5e1; padding: 0.8rem 1.5rem; display: flex; align-items: center; transition: all 0.2s; text-decoration: none; font-weight: 500; font-size: 0.95rem; border-left: 3px solid transparent; }
        .nav-link:hover, .nav-link.active { background-color: #0f172a; color: #60a5fa; border-left-color: #60a5fa; }
        .nav-link i { width: 24px; margin-right: 10px; }

        /* MAIN CONTENT STYLE */
        .main-content { margin-left: 260px; padding: 2rem; min-height: 100vh; transition: all 0.3s; }

        /* MOBILE MENU TOGGLE */
        .menu-toggle { display: none; font-size: 1.5rem; cursor: pointer; color: #333; margin-right: 1rem; }
        
        /* RESPONSIVE CSS (HP) */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); } 
            .main-content { margin-left: 0; padding: 1rem; } 
            .menu-toggle { display: block; } 
            
            
            .overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999; }
            .overlay.active { display: block; }
        }

        /* COMPONENTS */
        .stat-card { background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); display: flex; justify-content: space-between; align-items: center; border: 1px solid #e2e8f0; height: 100%; }
        .icon-box { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
        .table-card { background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); overflow: hidden; border: 1px solid #e2e8f0; }
        .table thead th { background-color: #f8fafc; color: #64748b; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; padding: 1rem; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
        .table tbody td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; white-space: nowrap; }
        .filter-input { font-size: 0.85rem; padding: 0.5rem; border-radius: 6px; border: 1px solid #cbd5e1; background: #fff; min-width: 100px; }
        .btn-action { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; transition: 0.2s; }
        .badge-status { font-size: 0.75rem; padding: 0.35em 0.8em; border-radius: 99px; font-weight: 600; }
        .bg-soft-success { background-color: #dcfce7; color: #166534; }
        .bg-soft-danger { background-color: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>

    <div class="overlay" id="overlay" onclick="toggleMenu()"></div>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand d-flex justify-content-between">
            <span><i class="fa-solid fa-layer-group me-2 text-primary"></i> FastPrint<span class="text-primary">Admin</span></span>
            <i class="fa-solid fa-xmark d-md-none cursor-pointer" onclick="toggleMenu()"></i>
        </div>
        <nav class="mt-4 d-flex flex-column flex-grow-1">
            <a href="#" class="nav-link active"><i class="fa-solid fa-box"></i> Produk</a>
            <a href="#" class="nav-link"><i class="fa-solid fa-chart-pie"></i> Kategori</a>
            <a href="#" class="nav-link"><i class="fa-solid fa-tags"></i> Status</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-bars menu-toggle" onclick="toggleMenu()"></i>
                <div>
                    <h4 class="fw-bold text-dark mb-0">Produk</h4>
                    <p class="text-muted small mb-0 d-none d-md-block">Overview inventaris gudang</p>
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

        <?php 
            $total = count($produk ?? []);
            $aktif = 0; $non_aktif = 0;
            if(!empty($produk)) {
                foreach($produk as $p) {
                    if($p->nama_status == 'bisa dijual') $aktif++; else $non_aktif++;
                }
            }
        ?>
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="stat-card border-start border-4 border-primary">
                    <div><h6 class="text-muted text-uppercase small fw-bold">TOTAL PRODUK</h6><h2 class="mb-0 fw-bold text-dark"><?= $total ?></h2></div>
                    <div class="icon-box bg-primary bg-opacity-10 text-primary"><i class="fa-solid fa-cubes"></i></div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stat-card border-start border-4 border-success">
                    <div><h6 class="text-muted text-uppercase small fw-bold">BISA DIJUAL</h6><h2 class="mb-0 fw-bold text-success"><?= $aktif ?></h2></div>
                    <div class="icon-box bg-success bg-opacity-10 text-success"><i class="fa-solid fa-check-circle"></i></div>
                </div>
            </div>
            <div class="col-6 col-md-4">
                <div class="stat-card border-start border-4 border-danger">
                    <div><h6 class="text-muted text-uppercase small fw-bold">TIDAK BISA DIJUAL</h6><h2 class="mb-0 fw-bold text-danger"><?= $non_aktif ?></h2></div>
                    <div class="icon-box bg-danger bg-opacity-10 text-danger"><i class="fa-solid fa-ban"></i></div>
                </div>
            </div>
        </div>

        <?php if($this->session->flashdata('msg')): ?>
            <div class="alert alert-success border-0 shadow-sm mb-4"><i class="fa-solid fa-check me-2"></i><?= $this->session->flashdata('msg') ?></div>
        <?php endif; ?>

        <div class="table-card">
            <div class="p-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center bg-white gap-2">
                <h6 class="fw-bold m-0"><i class="fa-solid fa-list me-2"></i>Data Inventory</h6>
                <div class="d-flex gap-2">
                    <a href="<?= site_url('produk/sync_api') ?>" class="btn btn-sm btn-outline-dark" onclick="return confirm('Tarik data baru?')"><i class="fa-solid fa-sync me-1"></i> Sync</a>
                    <a href="<?= site_url('produk/tambah') ?>" class="btn btn-sm btn-primary"><i class="fa-solid fa-plus me-1"></i> Baru</a>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="tabelProduk">
                    <thead>
                        <tr>
                            <th class="ps-4" style="min-width: 200px;">Produk <button class="btn btn-sm p-0 ms-1 text-muted" onclick="sortTable('nama')"><i class="fa-solid fa-sort"></i></button></th>
                            <th style="min-width: 150px;">Kategori</th>
                            <th style="min-width: 150px;">Harga <button class="btn btn-sm p-0 ms-1 text-muted" onclick="sortTable('harga')"><i class="fa-solid fa-sort"></i></button></th>
                            <th style="min-width: 120px;">Status</th>
                            <th class="text-center" style="min-width: 100px;">Aksi</th>
                        </tr>
                        <tr class="bg-light">
                            <td class="ps-4 py-2"><input type="text" class="filter-input w-100" placeholder="Filter Nama..." onkeyup="filterTable(0)"></td>
                            <td class="py-2"><input type="text" class="filter-input w-100" placeholder="Filter Kategori..." onkeyup="filterTable(1)"></td>
                            <td class="py-2"><input type="text" class="filter-input w-100" placeholder="Cari Harga..." onkeyup="filterTable(2)"></td>
                            <td class="py-2">
                                <select class="filter-input w-100" onchange="filterTable(3)">
                                    <option value="">Semua</option>
                                    <option value="bisa dijual" selected>BISA DIJUAL</option>
                                    <option value="tidak bisa dijual">TIDAK DIJUAL</option>
                                </select>
                            </td>
                            <td class="py-2"></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($produk)): ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted">Data kosong. Silakan Sync API.</td></tr>
                        <?php else: ?>
                            <?php foreach($produk as $p): ?>
                            <tr>
                                <td class="ps-4" data-nama="<?= strtolower($p->nama_produk) ?>" data-id="<?= $p->id_produk ?>">
                                    <div class="fw-bold text-dark text-truncate" style="max-width: 250px;"><?= $p->nama_produk ?></div>
                                    <small class="text-muted" style="font-size:0.75rem;">ID: #<?= $p->id_produk ?></small>
                                </td>
                                <td><span class="badge bg-light text-dark border fw-normal"><?= $p->nama_kategori ?></span></td>
                                <td class="fw-bold text-dark">Rp <?= number_format($p->harga, 0, ',', '.') ?></td>
                                <td data-status="<?= $p->nama_status ?>">
                                    <?php if($p->nama_status == 'bisa dijual'): ?>
                                        <span class="badge badge-status bg-soft-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-status bg-soft-danger">Non-Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= site_url('produk/edit/'.$p->id_produk) ?>" class="btn btn-action bg-light text-primary me-1"><i class="fa-solid fa-pen"></i></a>
                                    <a href="javascript:void(0);" class="btn btn-action bg-light text-danger" data-bs-toggle="modal" data-bs-target="#modalHapus" data-url="<?= site_url('produk/hapus/'.$p->id_produk) ?>"><i class="fa-solid fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <footer class="mt-5 text-center text-muted small pb-4">&copy; 2026 FastPrint.</footer>
    </div>

    <div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div class="mb-3 text-danger"><i class="fa-solid fa-trash-can fa-2x"></i></div>
                    <h6 class="fw-bold">Hapus Data?</h6>
                    <p class="small text-muted mb-4">Data tidak bisa dikembalikan.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                        <a href="#" id="btn-konfirmasi-hapus" class="btn btn-sm btn-danger px-3">Hapus</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // TOGGLE MENU MOBILE
        function toggleMenu() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        }

        const modalHapus = document.getElementById('modalHapus');
        modalHapus.addEventListener('show.bs.modal', function (event) {
            document.getElementById('btn-konfirmasi-hapus').href = event.relatedTarget.getAttribute('data-url');
        });

        // SORTING LOGIC
        let sortDir = { nama: 'asc', id: 'asc', harga: 'asc' };
        function sortTable(type) {
            const table = document.getElementById("tabelProduk").querySelector("tbody");
            const rows = Array.from(table.querySelectorAll("tr"));
            let dir = sortDir[type] === 'asc' ? 'desc' : 'asc';
            sortDir[type] = dir;
            rows.sort((a, b) => {
                let valA, valB;
                if(type === 'nama') {
                    valA = a.cells[0].getAttribute('data-nama'); valB = b.cells[0].getAttribute('data-nama');
                    return dir === 'asc' ? valA.localeCompare(valB) : valB.localeCompare(valA);
                } else if (type === 'id') {
                    valA = parseInt(a.cells[0].getAttribute('data-id')); valB = parseInt(b.cells[0].getAttribute('data-id'));
                    return dir === 'asc' ? valA - valB : valB - valA;
                } else if (type === 'harga') {
                    let textA = a.cells[2].textContent || a.cells[2].innerText;
                    let textB = b.cells[2].textContent || b.cells[2].innerText;
                    valA = parseInt(textA.replace(/[^0-9]/g, '')); valB = parseInt(textB.replace(/[^0-9]/g, ''));
                    return dir === 'asc' ? valA - valB : valB - valA;
                }
            });
            rows.forEach(row => table.appendChild(row));
        }

        // FILTER LOGIC (MULTI-COLUMN + EXACT MATCH STATUS + PARTIAL HARGA)
        function filterTable() {
            var inputs = document.getElementsByClassName("filter-input");
            var filterNama = inputs[0].value.toUpperCase();
            var filterKategori = inputs[1].value.toUpperCase();
            var filterHarga = inputs[2].value.replace(/[^0-9]/g, ''); 
            var filterStatus = inputs[3].value.toUpperCase();

            var table = document.getElementById("tabelProduk");
            var tr = table.getElementsByTagName("tr");

            for (var i = 2; i < tr.length; i++) {
                var showRow = true;
                
                // Nama
                var tdNama = tr[i].getElementsByTagName("td")[0];
                if (tdNama && tdNama.textContent.toUpperCase().indexOf(filterNama) === -1) showRow = false;

                // Kategori
                var tdKat = tr[i].getElementsByTagName("td")[1];
                if (tdKat && showRow && tdKat.textContent.toUpperCase().indexOf(filterKategori) === -1) showRow = false;

                // Harga (Contains)
                var tdHarga = tr[i].getElementsByTagName("td")[2];
                if (tdHarga && showRow) {
                    var hargaBersih = tdHarga.textContent.replace(/[^0-9]/g, '');
                    if (filterHarga !== "" && hargaBersih.indexOf(filterHarga) === -1) showRow = false;
                }

                // Status (Exact Match)
                var tdStatus = tr[i].getElementsByTagName("td")[3];
                if (tdStatus && showRow) {
                    var statusAsli = tdStatus.getAttribute("data-status").toUpperCase();
                    if (filterStatus !== "" && statusAsli !== filterStatus) showRow = false;
                }

                tr[i].style.display = showRow ? "" : "none";
            }
        }

        window.onload = function() { filterTable(); };
    </script>
</body>
</html>