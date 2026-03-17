<?php
$page_name = "Video Tutorials";
include 'include/header-main.php';

// Fetch public video tutorials
$sql = mysqli_query($conn, "SELECT * FROM video_tutorials WHERE visibility='public' ORDER BY created_at DESC");
$video_tutorials = [];
while($row = mysqli_fetch_assoc($sql)) {
    $video_tutorials[] = $row;
}
?>
    <div id="app">
        <div class="container-fluid p-0">
            <div class="appHeader">
                <div class="left">
                    <a href="#" class="headerButton goBack">
                        <i class="ri-arrow-left-line icon md hydrated"></i>
                    </a>
                    <div class="pageTitle">Video Tutorials</div>
                </div>
                <div class="right">
                </div>
            </div>
        </div>

        <div id="appCapsule">
            <div class="mb-4">
                <div class="search-wrapper">
                    <i class="ri-search-line search-icon"></i>
                    <input type="text" id="searchVideos" class="form-control" placeholder="Search tutorials...">
                </div>
            </div>

            <div class="row" id="videoContainer">
                <?php foreach($video_tutorials as $video): 
                    $thumb = "./uploads/video_tutorials/thumbnails/" . ($video['thumbnail'] ?? 'default.png');
                    $videoPath = "./uploads/video_tutorials/" . $video['video_path'];
                ?>
                <div class="col-12 mb-4 video-card" data-title="<?= strtolower($video['title']) ?>">
                    <div class="card rounded shadow-sm position-relative overflow-hidden">
                        <div class="video-thumb">
                            <img src="<?= $thumb ?>" alt="<?= htmlspecialchars($video['title']) ?>">
                            <div class="thumb-overlay"></div>

                            <button class="play-btn" data-video="<?= $videoPath ?>">
                                <span class="play-icon">
                                    <i class="ri-play-fill"></i>
                                </span>
                                <span class="play-text">Play</span>
                            </button>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($video['title']) ?></h5>
                            <p class="card-text text-muted"><?= htmlspecialchars($video['caption']) ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php
            include("include/bottom-menu.php")
        ?>
    </div>

    <!-- Video Modal -->
    <div id="videoModal" class="modal-overlay">
        <div class="modal-content">
            <button class="close-btn" id="closeVideoModal">
                <i class="ri-close-line"></i>
            </button>

            <video id="modalVideo" controls></video>
        </div>
    </div>

    <style>
        /* ================= CARD ================= */
        .video-card .card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.25s ease;
        }

        .video-card .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.12);
        }

        /* Thumbnail */
        .video-thumb {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .video-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Subtle dark overlay */
        .thumb-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.18);
            pointer-events: none;
        }

        /* ================= PLAY BUTTON ================= */

        .video-card .play-btn {
            position: absolute;
            bottom: 14px;
            left: 14px;

            display: flex;
            align-items: center;
            gap: 10px;

            padding: 6px 14px 6px 6px;
            border-radius: 40px;

            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(6px);

            border: 1px solid rgba(255,255,255,0.25);
            color: #fff;

            font-size: 14px;
            font-weight: 600;

            cursor: pointer;
            overflow: hidden;
            transition: all 0.25s ease;
        }

        /* Glossy circular icon */
        .play-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: linear-gradient(
                to bottom,
                rgba(255,255,255,0.35),
                rgba(255,255,255,0.08)
            );

            border: 1px solid rgba(255,255,255,0.4);
            backdrop-filter: blur(8px);
        }

        .play-icon i {
            font-size: 16px;
            color: white;
        }

        /* Shine animation */
        .video-card .play-btn::before {
            content: "";
            position: absolute;
            top: -60%;
            left: -80%;
            width: 50%;
            height: 220%;
            background: rgba(255,255,255,0.2);
            transform: rotate(25deg);
            transition: 0.6s;
        }

        .video-card .play-btn:hover::before {
            left: 140%;
        }

        .video-card .play-btn:hover {
            background: rgba(0, 0, 0, 0.75);
            transform: translateY(-2px) scale(1.04);
        }

        /* Title */
        .video-card .card-title {
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 6px;
            color: #111;
        }

        /* ================= MODAL OVERLAY ================= */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(4px);
            z-index: 5000;

            /* Center content */
            display: none;
            align-items: center;
            justify-content: center;

            padding: 20px;
        }

        /* ================= MODAL CONTENT ================= */

        .modal-content {
            position: relative;
            background: #111;
            border-radius: 16px;

            width: 100%;
            max-width: 900px;
            max-height: 85vh;

            padding: 20px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.6);
        }

        /* Make video responsive */
        .modal-content video {
            width: 100%;
            height: auto;
            max-height: 70vh;
            border-radius: 12px;
            display: block;
        }

        /* Close Button */
        .close-btn {
            position: absolute;
            top: 14px;
            right: 14px;

            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: none;

            background: rgb(150 146 146 / 50%);
            backdrop-filter: blur(6px);

            color: #fff;
            font-size: 20px;

            display: flex;
            align-items: center;
            justify-content: center;

            cursor: pointer;
            z-index: 10;
        }

        .close-btn:hover {
            background: rgba(255,255,255,0.3);
        }

        /* Mobile improvement */
        @media (max-width: 576px) {
            .modal-content {
                padding: 14px;
            }

            .close-btn {
                width: 36px;
                height: 36px;
                font-size: 18px;
            }
        }

        /* ================= SEARCH BAR ================= */

        .search-wrapper {
            position: relative;
            width: 100%;
        }

        .search-wrapper input {
            width: 100%;
            height: 52px;
            padding: 0 16px 0 48px; /* space for icon */
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            background: #ffffff;

            font-size: 14px;
            font-weight: 500;
            color: #111;

            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            transition: all 0.25s ease;
        }

        /* Icon positioning */
        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #9ca3af;
            pointer-events: none;
            transition: 0.25s ease;
        }

        /* Focus state */
        .search-wrapper input:focus {
            outline: none;
            border-color: #111;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        /* Animate icon on focus */
        .search-wrapper input:focus + .search-icon {
            color: #111;
        }

        /* Optional subtle hover */
        .search-wrapper:hover input {
            box-shadow: 0 12px 30px rgba(0,0,0,0.06);
        }
    </style>

<script>
$(document).ready(function(){

    // Filter videos by title
    $('#searchVideos').on('input', function(){
        let val = $(this).val().toLowerCase();
        $('.video-card').each(function(){
            let title = $(this).data('title');
            $(this).toggle(title.includes(val));
        });
    });

    // Play video in modal
    $('.play-btn').on('click', function(){
        let src = $(this).data('video');
        $('#modalVideo').attr('src', src)[0].play();
        $('#videoModal')
        .css('display', 'flex')
        .hide()
        .fadeIn(200);
        $('body').css('overflow', 'hidden'); // prevent background scroll
    });

    function closeModal() {
        $('#modalVideo')[0].pause();
        $('#modalVideo').attr('src', '');
        $('#videoModal').fadeOut(200);
        $('body').css('overflow', 'auto');
    }

    // Close button click
    $('#closeVideoModal').on('click', function(){
        closeModal();
    });

    // Click outside modal content
    $('#videoModal').on('click', function(e){
        if (!$(e.target).closest('.modal-content').length) {
            closeModal();
        }
    });

    // ESC key closes modal
    $(document).on('keydown', function(e){
        if (e.key === "Escape") {
            closeModal();
        }
    });

});
</script>

<?php include 'include/footer-main.php'; ?>