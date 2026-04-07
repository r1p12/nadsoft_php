


<?php include("header.php");?>
<?php include("db.php");?>
<div class="container mt-5">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Business Listing</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
            + Add Business
        </button>
    </div>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Business Name</th>
						<th>Address</th>
                        <th>Contact</th>
						<th>Email</th>
                        <th>Action</th>
						<th>Ratings</th>
                    </tr>
                </thead>
                <tbody id="tableData"></tbody>
            </table>
        </div>
    </div>

</div>

<div class="modal fade" id="addModal">
  <div class="modal-dialog">
    <form id="addForm" class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Add Business</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <div class="mb-3">
            <label class="form-label">Business Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Address</label>
            <textarea name="address" class="form-control"></textarea>
        </div>

        <div class="row">
            <div class="col">
                <label>Phone</label>
                <input type="text" name="phone"    pattern="[0-9]{10}" 
       maxlength="10"
      
        class="form-control" required>
            </div>
            <div class="col">
                <label>Email</label>
                <input type="email" name="email" class="form-control">
            </div>
        </div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-success">Save</button>
      </div>

    </form>
  </div>
</div>

<div class="modal fade" id="editModal">
  <div class="modal-dialog">
    <form id="editForm" class="modal-content">

      <input type="hidden" name="id" id="edit_id">

      <div class="modal-header bg-warning">
        <h5>Edit Business</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">

        <input type="text" name="name" id="edit_name" class="form-control mb-2">

        <textarea name="address" id="edit_address" class="form-control mb-2"></textarea>

        <input type="text" name="phone"  maxlength="10" id="edit_phone" class="form-control mb-2">

        <input type="email" name="email" id="edit_email" class="form-control">

      </div>

      <div class="modal-footer">
        <button class="btn btn-warning">Update</button>
      </div>

    </form>
  </div>
</div>

<div class="modal fade" id="ratingModal">
  <div class="modal-dialog">
    <form id="ratingForm" class="modal-content">

      <input type="hidden" name="business_id" id="business_id">
      <input type="hidden" name="rating" id="rating_value">

      <div class="modal-header bg-success text-white">
        <h5>Give Rating</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body text-center">

        <input type="text" name="name" class="form-control mb-2" placeholder="Name" required>
        <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
        <input type="text" name="phone"   maxlength="10" class="form-control mb-3" placeholder="Phone" required>

      
        <div id="user_rating"></div>

      </div>

      <div class="modal-footer">
        <button class="btn btn-success w-100">Submit</button>
      </div>

    </form>
  </div>
</div>

<script>


$(document).ready(function(){

    loadData();

    $("#addForm").submit(function(e){
        e.preventDefault();

        $.ajax({
            url: "ajax.php",
            type: "POST",
            data: $(this).serialize() + "&action=add",
            success: function(res){

                // close modal (Bootstrap 5)
                var modal = bootstrap.Modal.getInstance(document.getElementById('addModal'));
                modal.hide();

                loadData();
            }
        });
    });

});

function loadData(){
    $.ajax({
        url: "ajax.php",
        type: "POST",
        data: {action: "fetch"},
        success: function(data){
            $("#tableData").html(data);
            $('.rating').raty({
                readOnly: true,
                half: true,
                score: function(){
                    return $(this).attr("data-score");
                }
            });
        }
    });
}
function editBusiness(id){
    $.ajax({
        url: "ajax.php",
        type: "POST",
        data: {id: id, action: "get"},
        success: function(res){

            let data = JSON.parse(res);

            $("#edit_id").val(data.id);
            $("#edit_name").val(data.name);
            $("#edit_address").val(data.address);
            $("#edit_phone").val(data.phone);
            $("#edit_email").val(data.email);

          
            var myModal = new bootstrap.Modal(document.getElementById('editModal'));
            myModal.show();
        }
    });
}


$("#editForm").submit(function(e){
    e.preventDefault();

    $.ajax({
        url: "ajax.php",
        type: "POST",
        data: $(this).serialize() + "&action=update",
        success: function(){

         
            var modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
            modal.hide();

           
            loadData();
        }
    });
});

function deleteBusiness(id){

    if(confirm("Are you sure you want to delete this business?")){

        $.ajax({
            url: "ajax.php",
            type: "POST",
            data: {id: id, action: "delete"},
            success: function(){

             
                loadData();
            }
        });

    }
}



function openRating(id){

    $("#business_id").val(id);

    $("#ratingForm")[0].reset();

   
    if($('#user_rating').data('raty')){
        $('#user_rating').raty('destroy');
    }

    $('#user_rating').raty({
        half: true,
        click: function(score){
            $("#rating_value").val(score);
        }
    });

    var modal = new bootstrap.Modal(document.getElementById('ratingModal'));
    modal.show();
}

$("#ratingForm").submit(function(e){
    e.preventDefault();

    $.ajax({
        url: "ajax.php",
        type: "POST",
        data: $(this).serialize() + "&action=rate",
        success: function(){

          
            var modal = bootstrap.Modal.getInstance(document.getElementById('ratingModal'));
            modal.hide();

           
            loadData();
        }
    });
});
</script>

