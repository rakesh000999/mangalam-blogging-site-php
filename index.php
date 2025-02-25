<?php
include 'connection.php';
include 'navbar.php';

$fetchSql = "SELECT p.post_id, u.user_id, u.username, p.title, p.blog_image, p.excerpt, p.created_at, profile_picture
             FROM posts p
             INNER JOIN users u ON u.user_id = p.user_id
             INNER JOIN user_profiles up ON u.user_id = up.user_id
             ORDER BY p.created_at DESC";

$fetchResult = mysqli_query($conn, $fetchSql);

?>

<link rel="stylesheet" href="style.css">

<main class="row container-lg mx-auto ">
    <div class="col-lg-8">
        <?php
        while ($result = mysqli_fetch_assoc($fetchResult)) {
            $postId = $result['post_id'];
            ?>

            <div class="card d-flex m-2 p-2">
                <div class="mb-2">
                    <img src="uploads/<?php echo !empty($result['profile_picture']) ? $result['profile_picture'] : 'default.png'; ?>"
                        alt="Profile Image" class="image rounded-circle" style="width: 32px; height: 32px;">

                    <span>
                        <!-- <a href="viewOthersProfile.php?user_id=<?php echo $result['user_id'] ?>"
                            class="text-decoration-none text-dark">
                            <?php echo $result['username']; ?>
                        </a> -->

                        <a href="viewProfile.php?user_id=<?php echo $result['user_id']; ?>"
                            class="text-decoration-none text-dark">
                            <?php echo $result['username']; ?>
                        </a>
                    </span>
                </div>

                <a href="read-blog.php?id=<?php echo $postId; ?>" class="text-decoration-none text-dark">
                    <div class="d-flex justify-content-between ">
                        <div class="w-75">
                            <div>
                                <h3 class="fw-bolder"><?php echo $result['title']; ?></h3>
                                <h5 class="text-secondary"><?php echo $result['excerpt']; ?></h5>
                            </div>

                            <div class="d-flex gap-4 justify-content-between">
                                <div class="d-flex gap-4">
                                    <div class="text-secondary"><?php echo $result['created_at']; ?></div>
                                    <div><i class="fa-solid fa-heart"></i> 0</div>
                                    <div>
                                        <?php
                                        $commentCount = "SELECT COUNT(*) as total FROM comments WHERE post_id = " . $postId;
                                        $commentCountResult = mysqli_query($conn, $commentCount);
                                        $commentCountData = mysqli_fetch_assoc($commentCountResult);
                                        $commentCount = $commentCountData['total'];
                                        ?>

                                        <i class="fa-solid fa-comment"></i> <?php echo $commentCount ?>
                                    </div>
                                </div>

                                <div class="text-secondary">
                                    <a class="nav-link dropdown-toggle" href="#" id="dropdownMenuLink" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <li><a class="dropdown-item" href="profile.php">Follow author</a></li>
                                        <li>

                                            <a class="dropdown-item" href="report.php?postId=<?php echo $postId; ?>">
                                                Report
                                            </a>

                                        </li>

                                    </ul>

                                </div>
                            </div>
                        </div>
                        <div class="w-25">
                            <img src="uploads/<?php echo $result['blog_image'] ?>" class="rounded " alt="blog_image"
                                style="width : 150px; height: 120px; object-fit: cover;">
                        </div>
                    </div>
                </a>
            </div>
            <?php
        }
        ?>
    </div>

    <!-- Top Posts -->
    <?php
    $selectTopComment = $conn->query("
    SELECT p.post_id, u.username, p.title, COUNT(c.comment_id) AS comment_count, up.profile_picture
    FROM posts AS p
    LEFT JOIN comments AS c ON p.post_id = c.post_id
    LEFT JOIN users AS u ON p.user_id = u.user_id
    LEFT JOIN user_profiles AS up ON u.user_id = up.user_id
    GROUP BY p.post_id, u.username, p.title
    ORDER BY comment_count DESC
    LIMIT 5
");
    ?>

    <div class="col-lg-4">
        <div class="text-success fw-bold h5">Top Posts:</div>
        <?php while ($result = $selectTopComment->fetch_assoc()) {

            ?>
            <a href="read-blog.php?id=<?php echo $result['post_id']; ?>" class="text-decoration-none text-dark">
                <div class="mt-3 card p-2">
                    <div>
                        <img src="uploads/<?php echo !empty($result['profile_picture']) ? $result['profile_picture'] : 'default.png'; ?>"
                            alt="Profile Image" class="image rounded-circle" style="width: 32px; height: 32px;">

                        <span class="text-dark fw-bolder"><?php echo $result['username']; ?></span>
                    </div>
                    <p class="h-4"><?php echo $result['title'] ?></p>
                </div>
            </a>
            <?php
        } ?>

    </div>
</main>