@extends('client.client_dashboard')
@section('client')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<div class="page-content">
  <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
          <div class="col-12">
              <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                  <h4 class="mb-sm-0 font-size-18">Edit Coupon</h4>

                  <div class="page-title-right">
                      <ol class="breadcrumb m-0">
                          <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                          <li class="breadcrumb-item active">Edit Coupon</li>
                      </ol>
                  </div>

              </div>
          </div>
      </div>
      <!-- end page title -->

      <div class="row">
          <div class="col-xl-12 col-lg-12">

<div class="card">
<div class="card-body p-4">
<form id="myForm" action="{{ route('coupon.update') }}" method="post">
    @csrf
    <input type="hidden" name="id" value="{{ $coupon->id }}">
<div class="row">
    <div class="col-xl-6 col-md-6">
      <div class="form-group mb-3">
          <label for="example-text-input" class="form-label">Coupon Name</label>
          <input class="form-control" name="coupon_name" type="text" value="{{ $coupon->coupon_name }}"  id="example-text-input">
      </div> 
    </div>
    <div class="col-xl-6 col-md-6">
      <div class="form-group mb-3">
          <label for="example-text-input" class="form-label">Coupon Desc</label>
          <textarea name="coupon_desc" id="basicpill-address-input" rows="1" class="form-control" placeholder="Enter your Address">{{ $coupon->coupon_desc }}</textarea>

      </div> 
    </div>
    <div class="col-xl-6 col-md-6">
      <div class="form-group mb-3">
          <label for="example-text-input" class="form-label">Coupon Discount</label>
          <input class="form-control" name="discount" type="text" value="{{ $coupon->discount }}" id="example-text-input">
      </div> 
    </div>
    <div class="col-xl-6 col-md-6">
      <div class="form-group mb-3">
          <label for="example-text-input" class="form-label">Coupon Validity</label>
          <input class="form-control" name="validity" type="date" id="example-text-input" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="{{ $coupon->validity }}">
      </div> 
    </div>
    <div class="mt-4">
      <button type="submit" class="btn btn-primary waves-effect waves-light">Save Changes</button>
  </div>
   
    
</div>
</form>
</div>
</div>
         
              <!-- end tab content -->
          </div>
          <!-- end col -->

         
          <!-- end col -->
      </div>
      <!-- end row -->
      
  </div> <!-- container-fluid -->
</div>


<script type="text/javascript">
    $(document).ready(function(){
        $('#image').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showImage').attr('src',e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        })
    })
</script>

{{-- validation script --}}
<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                menu_name: {
                    required : true,
                }, 
                image: {
                    required : true,
                }, 
                
            },
            messages :{
                menu_name: {
                    required : 'Please Enter Menu Name',
                }, 
                image: {
                    required : 'Please Upload Menu Image',
                },
                 

            },
            errorElement : 'span', 
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });
    
</script>
@endsection
