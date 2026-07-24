<?php
$baseUrl = '/JobCV';

require_once __DIR__ . '/../layouts/header.php';
?>

<section class="py-5 bg-light">
    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                <div class="card border-0 shadow-sm">

                    <div class="card-body p-4">

                        <h3 class="fw-bold mb-4">
                            <i class="fa-solid fa-paper-plane text-primary me-2"></i>
                            Ứng tuyển công việc
                        </h3>

                        <!-- Thông tin công việc -->
                        <div class="border rounded p-3 mb-4 bg-light">

                            <h5 class="fw-bold mb-2">
                                <?= htmlspecialchars($job['TieuDe']) ?>
                            </h5>

                            <p class="mb-1">
                                <i class="fa-solid fa-location-dot text-danger me-2"></i>
                                <?= htmlspecialchars($job['DiaChiLamViec']) ?>
                            </p>

                            <p class="mb-0">
                                <i class="fa-solid fa-money-bill-wave text-success me-2"></i>
                                <?= number_format($job['MucLuong'], 0, ',', '.') ?> VNĐ
                            </p>

                        </div>

                        <!-- CV được sử dụng -->
                        <div class="mb-4">

                            <h5 class="fw-bold mb-3">
                                CV ứng tuyển
                            </h5>

                            <div class="border rounded p-3">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div>
                                        <i class="fa-solid fa-file-lines text-primary fs-3 me-2"></i>

                                        <strong>
                                            <?= htmlspecialchars($cv['TieuDe']) ?>
                                        </strong>

                                        <br>

                                        <small class="text-muted">
                                            Vị trí mong muốn:
                                            <?= htmlspecialchars($cv['ViTriMongMuon']) ?>
                                        </small>
                                    </div>

                                    <a
                                        href="/JobCV/index.php?route=cv/detail&maCV=<?= urlencode($cv['MaCV']) ?>"
                                        class="btn btn-outline-primary btn-sm"
                                    >
                                        Xem CV
                                    </a>

                                </div>

                            </div>

                        </div>

                        <!-- Form ứng tuyển -->
                        <form
                            method="POST"
                            action="/JobCV/index.php?route=jobs/apply-submit"
                        >

                            <input
                                type="hidden"
                                name="maTinTuyenDung"
                                value="<?= htmlspecialchars($job['MaTinTuyenDung']) ?>"
                            >

                            <div class="mb-4">

                                <label class="form-label fw-bold">
                                    Thư giới thiệu
                                    <span class="text-muted fw-normal">
                                        (Không bắt buộc)
                                    </span>
                                </label>

                                <textarea
                                    name="coverLetter"
                                    class="form-control"
                                    rows="7"
                                    placeholder="Giới thiệu ngắn gọn về bản thân và lý do bạn phù hợp với vị trí này..."
                                ></textarea>

                            </div>

                            <div class="d-flex justify-content-between">

                                <a
                                    href="/JobCV/index.php?route=jobs/detail&maTinTuyenDung=<?= urlencode($job['MaTinTuyenDung']) ?>"
                                    class="btn btn-secondary"
                                >
                                    Quay lại
                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-primary px-4"
                                >
                                    <i class="fa-solid fa-paper-plane me-2"></i>
                                    Gửi hồ sơ ứng tuyển
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>
</section>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>