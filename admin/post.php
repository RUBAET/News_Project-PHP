<?php include "header.php"; ?>
  <div id="admin-content">
      <div class="container">
          <div class="row">
              <div class="col-md-10">
                  <h1 class="admin-heading">All Posts</h1>
              </div>
              <div class="col-md-2">
                  <a class="add-new" href="add-post.php">add post</a>
              </div>
              <div class="col-md-12">
        <?php 
        include "config.php";
        $limit=4;
        if(isset($_GET['page'])){
        $page=$_GET['page'];
      }else{
        $page=1;
      }
        $offset=($page-1)*$limit;
       
        if($_SESSION["user_role"]==1){
        $sql= "SELECT p.post_id,p.title,p.category,p.post_date,p.author,u.username,c.category_name FROM post  p JOIN user  u 
        ON P.author=u.user_id
        JOIN category  c ON p.category=c.category_id
         ORDER BY post_date LIMIT {$offset},{$limit}";
       }elseif ($_SESSION["user_role"]==0) {
         
       
        $sql= "SELECT p.post_id,p.title,p.category,p.post_date,p.author,u.username,c.category_name FROM post  p 
        LEFT JOIN category  c ON p.category=c.category_id
        LEFT JOIN user  u ON P.author=u.user_id 
        
        WHERE p.author={$_SESSION["user_id"]}
         ORDER BY post_date LIMIT {$offset},{$limit}";
        }
       
      $result= mysqli_query($conn, $sql) or die("query unsuccessful.");
       if(mysqli_num_rows($result)>0){

    ?>
                  <table class="content-table">
                      <thead>
                          <th>S.No.</th>
                          <th>Title</th>
                          <th>Category</th>
                          <th>Date</th>
                          <th>Author</th>
                          <th>Edit</th>
                          <th>Delete</th>
                      </thead>
                      <tbody>
                        <?php 

                        while($row= mysqli_fetch_assoc($result)){
                        ?>
                          <tr>
                              <td class='id'><?php echo $row["post_id"];?></td>
                              <td><?php echo $row["title"];?></td>
                              <td><?php echo $row["category_name"];?></td>
                              <td><?php echo $row["post_date"];?></td>
                              <td><?php echo $row["username"];?></td>

                              <td class='edit'><a href='update-post.php?id=<?php echo $row["post_id"];?>'><i class='fa fa-edit'></i></a></td>
                              <td class='delete'><a href='delete-post.php?id=<?php echo $row["post_id"];?> & cat_id=<?php echo $row["category"];?>'><i class='fa fa-trash-o'></i></a></td>
                          </tr>
                        <?php }
                        ?>
                         
                      </tbody>
                  </table>
                  <?php 
                }
                    if($_SESSION["user_role"]==0)
                    {
                      $sql1= "SELECT * FROM post WHERE author={$_SESSION["user_id"]} ";
                    }
                    elseif ($_SESSION["user_role"]==1) {
                      
                    $sql1= "SELECT * FROM post";
                  }
                    $result1=mysqli_query($conn,$sql1) or die("query failed!");
                    if(mysqli_num_rows($result1)>0){
                      $tot_record=mysqli_num_rows($result1);
                      
                      $tot_page=ceil($tot_record/$limit);
                      echo "<ul class='pagination admin-pagination'>";
                      if($page>1){
                        echo "<li><a href='post.php?page=".($page-1)."'> Prev</a></li>";
                      }
                      for($i=1;$i<=$tot_page;$i++){
                        if($i==$page){
                          $active="active";
                        }else{
                          $active="";
                        }
                         echo "<li class='".$active."'><a href='post.php?page=".$i." '> ".$i." </a></li>";
                        }
                         if($tot_page>$page){
                        echo "<li><a href='post.php?page=".($page+1)."'> Next</a></li>";
                      }
                     echo "</ul>";
                    }
                    else{
                      echo "<h3 style='color:red;text-align:center;margin:10px 0;'>No Post Found</h3>";
                    }
                ?>
                        
                  
              </div>
          </div>
      </div>
  </div>
<?php include "footer.php"; ?>