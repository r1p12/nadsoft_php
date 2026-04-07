<?php
include("db.php");

// ================= FETCH DATA =================
if($_POST['action'] == "fetch"){

    $res = $conn->query("
        SELECT b.*, IFNULL(AVG(r.rating),0) as avg_rating
        FROM businesses b
        LEFT JOIN ratings r ON b.id = r.business_id
        GROUP BY b.id
        ORDER BY b.id DESC
    ");

    if($res->num_rows > 0){
        while($row = $res->fetch_assoc()){

            echo "<tr>
                <td>{$row['id']}</td>

                <td>
                    <strong>{$row['name']}</strong><br>
					 </td>
					 <td>
                    <small>{$row['address']}</small>
                </td>

                <td>
                    {$row['phone']}<br>
					 </td> <td> 
                    {$row['email']}
                </td>

               

                <td>
                    <button class='btn btn-warning btn-sm' onclick='editBusiness({$row['id']})'>Edit</button>
                    <button class='btn btn-danger btn-sm' onclick='deleteBusiness({$row['id']})'>Delete</button>
                </td>
 <td>
        <div class='rating' data-score='".$row['avg_rating']."'
             onclick='openRating(".$row['id'].")'></div>
        <small>".number_format($row['avg_rating'],1)." / 5</small>
    </td>
				
            </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No Data Found</td></tr>";
    }
}


// ================= ADD BUSINESS =================
if($_POST['action'] == "add"){

    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $conn->query("
        INSERT INTO businesses (name,address,phone,email)
        VALUES ('$name','$address','$phone','$email')
    ");
}


// ================= GET SINGLE BUSINESS =================
if($_POST['action'] == "get"){

    $id = $_POST['id'];

    $res = $conn->query("SELECT * FROM businesses WHERE id='$id'");
    echo json_encode($res->fetch_assoc());
}


// ================= UPDATE BUSINESS =================
if($_POST['action'] == "update"){

    $id = $_POST['id'];

    $conn->query("
        UPDATE businesses SET
        name='{$_POST['name']}',
        address='{$_POST['address']}',
        phone='{$_POST['phone']}',
        email='{$_POST['email']}'
        WHERE id='$id'
    ");
}


// ================= DELETE BUSINESS =================
if($_POST['action'] == "delete"){

    $id = $_POST['id'];

    $conn->query("DELETE FROM businesses WHERE id='$id'");
}


// ================= RATING LOGIC =================
if($_POST['action'] == "rate"){

    $business_id = $_POST['business_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $rating = $_POST['rating'];

    // check existing rating
    $check = $conn->query("
        SELECT id FROM ratings 
        WHERE business_id='$business_id'
        AND (email='$email' OR phone='$phone')
    ");

    if($check->num_rows > 0){

        // UPDATE existing rating
        $conn->query("
            UPDATE ratings SET
            name='$name',
            rating='$rating'
            WHERE business_id='$business_id'
            AND (email='$email' OR phone='$phone')
        ");

    } else {

        // INSERT new rating
        $conn->query("
            INSERT INTO ratings (business_id,name,email,phone,rating)
            VALUES ('$business_id','$name','$email','$phone','$rating')
        ");
    }
}
?>
