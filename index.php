<?php
include 'connection.php';
include 'navbar.php';

// Fetch posts along with user information
$fetchSql = "SELECT post_id, username, title, excerpt, created_at 
             FROM users 
             INNER JOIN posts ON users.user_id = posts.user_id
             ORDER BY created_at DESC";
$fetchResult = mysqli_query($conn, $fetchSql);

// Fetch comment counts grouped by post_id
$selectComment = "SELECT post_id, COUNT(comment_id) AS comment_count 
                  FROM comments 
                  GROUP BY post_id";
$selectCommentResult = mysqli_query($conn, $selectComment);

// Store comment counts in an associative array
$commentCounts = [];
while ($commentRow = mysqli_fetch_assoc($selectCommentResult)) {
    $commentCounts[$commentRow['post_id']] = $commentRow['comment_count'];
}


?>

<link rel="stylesheet" href="style.css">

<main class="row container-lg mx-auto ">
    <div class="col-lg-8">
        <?php
        while ($result = mysqli_fetch_assoc($fetchResult)) {
            $postId = $result['post_id'];
            // Check if this post has comments, otherwise default to 0
            $commentCount = isset($commentCounts[$postId]) ? $commentCounts[$postId] : 0;
            ?>
            <div class="card d-flex m-2 p-2">
                <div>
                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile Image" class="image">
                    <span>
                        <a href="viewOthersProfile.php" class="text-decoration-none text-dark">
                            <?php echo $result['username']; ?>
                        </a>
                    </span>
                </div>
                <a href="read-blog.php?id=<?php echo $postId; ?>" class="text-decoration-none text-dark">
                    <div class="d-flex">
                        <div class="w-75">
                            <h2 class="fw-bolder"><?php echo $result['title']; ?></h2>
                            <h4><?php echo $result['excerpt']; ?></h4>
                        </div>
                        <div class="w-25 object-fit-fill">
                            <img src="https://i0.wp.com/picjumbo.com/wp-content/uploads/beautiful-beach-free-image-after-sunset-sky-free-photo.jpeg?w=600&quality=80"
                                alt="Blog Image" class="w-100">
                        </div>
                    </div>
                    <div class="d-flex gap-4">
                        <div><?php echo $result['created_at']; ?></div>
                        <div>👍 0</div>
                        <div><?php echo ($commentCount === 0) ? '' : "💬" . $commentCount; ?></div>
                    </div>
                </a>
            </div>
            <?php
        }
        ?>
    </div>


    <!-- Top Posts -->
    <?php
    $select = $conn->query("SELECT post_id, COUNT(comment_id) as comment_c FROM comments GROUP BY post_id");

    ?>
    <div class="col-lg-4">
        <div class="text-success fw-bold h5">Top Posts:</div>
        <?php foreach (range(1, 5) as $i) { ?>
            <?php while ($result = $select->fetch_assoc()) {
                ?>
                <div class="mt-3">
                    <div>
                        <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Profile Image" class="image">
                        <span><?php echo $result['username']; ?></span>
                    </div>
                    <p class="h-4"><?php echo $result['post_id'] ?></p>
                </div>
                <?php
            } ?>

        <?php } ?>
    </div>
</main>