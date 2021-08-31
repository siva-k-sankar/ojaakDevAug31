@extends('back.layouts.app')
@section('styles')
<style>
   ul {
  padding: 0;
  margin: 0;
}

ul li {
  margin: 3px;  
  list-style: none;
} 
</style>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js" defer ></script>
@endsection
@section('content')

  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Premium Plans
      </h1>
      
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <!-- left column -->
            <form action="{{route('admin.premiumadsplans.add')}}" method="post" enctype="multipart/form-data">
            <div class="col-md-6">
              <!-- general form elements -->
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Add a New Plan<span></span></h3>
                </div>
                <!-- /.box-header -->
                
                
                     <div class="box-body">
                        
                            @csrf
                            <div class="form-group @error('category') has-error  @enderror">
                                <label for="caategory">Category:</label>
                                <select class="js-example-basic-multiple form-control" id="caategory" name="category[]" multiple="multiple" required="">
                                    @foreach($catelist as $cate)  
                                        <option value="{{$cate->id}}">{{$cate->name}}</option>
                                    @endforeach
                                </select>
                                <!-- <input type="text" class="form-control" id="caategory" placeholder="" name="category" value="{{ old('category') }}" required=""> -->
                                @error('category')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('name') has-error  @enderror">
                                <label for="name">Plan Name:</label>
                                <input type="text" class="form-control" id="name" placeholder="" name="name" value="{{ old('name') }}" required="">
                                @error('name')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-group @error('advalidity') has-error  @enderror">
                                <label for="advalidity">Ad Validity / Wallet  Validity:</label>
                                <input type="number" class="form-control" id="advalidity" placeholder="" name="advalidity" value="{{ old('advalidity') }}" required="">
                                @error('advalidity')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('points') has-error  @enderror">
                                <label for="points">Ads points:</label>
                                <input type="text" class="form-control" id="points" placeholder="" name="points" value="{{ old('points') }}" required="">
                                @error('points')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('discount') has-error  @enderror">
                                <label for="discount">Discount:(<i class="fa fa-inr"></i> / <i class="fa fa-percent"></i>)</label>
                                <input type="text" class="form-control" id="discount" placeholder="" name="discount" value="{{ old('discount','0') }}" required="">
                                @error('discount')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group @error('comment') has-error  @enderror">
                                <label for="comment">Comment:</label>
                                <textarea class="form-control" id="comment" placeholder="" name="comment"></textarea> 
                                @error('comment')
                                    <span class="help-block" role="alert">
                                        <strong class="text-red">{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                    </div>
             </div>
              <!-- /.box -->
            </div>
        <!--/.col (left) -->
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">QUANTITY Details<span></span></h3>
                    </div> 
                    <div class="box-body">
                        <input name="detailscount" id="detailscount" class="form-control" type="hidden"   value="1" />
                        <button id="addMore" class="btn btn-primary">Add More Quantity</button>
                        <button id="removeMore" class="btn btn-primary">Remove Last Quantity</button>

                        <ul id="fieldList">
                            <li>
                              <strong class="text-red">Quantity Record NO : 1 </strong>
                            </li>
                            <li>
                                <label >Quantity</label>  
                              <input name="quantity[]" class="form-control" type="number" placeholder="Quantity" required="" />
                            </li>
                            <li>
                                <label >Price</label> 
                              <input name="price[]" class="form-control" type="number" placeholder="Price" required="" />
                            </li>
                            <li>
                                <label >Discount:(<i class="fa fa-inr"></i>)</label> 
                              <input name="discounts[]" class="form-control" type="number" placeholder="discounts" />
                            </li>
                            <li>
                                <label >Validity for ad Quantity (Represents days)</label> 
                              <input name="planvalitity[]" class="form-control" type="number" placeholder="Valitity Represent Days" required="" />
                            </li>
                        </ul>
                          
                    </div>
                    <div class="box-footer">
                        
                        <a style="margin: 3px;"class="btn btn-primary" href="{{ URL::previous() }}">Back</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>   
                </div>
            </div>
            </form>
        </div>
        <!-- /.row -->
      
    </section>
    <!-- /.content -->
  </div>
  
    

@endsection
@section('scripts')
<script>
    $(function() {
        var i=1;
        if(i==1){
            $("#removeMore").hide();
        }else{
            $("#removeMore").show();
        }
        $("#addMore").click(function(e) {
            e.preventDefault();
            $("#removeMore").show();
            if(i==4){
                $("#addMore").hide();
                exit();
            }else{
               i=i+1;
               $('#detailscount').val(i);
               if(i==4){
                    $("#addMore").hide();
               } 
            }
            
            var details="Quantity Record NO : "+i;
            $("#fieldList").append("<li class='"+i+"'>&nbsp;</li>");
             $("#fieldList").append("<li class='"+i+"'><strong class='text-red'>"+details+"</strong></li>");
            $("#fieldList").append("<li class='"+i+"'><label >Quantity</label><input type='number'class='form-control' name='quantity[]' placeholder='Quantity' required='' /></li>");
            $("#fieldList").append("<li class='"+i+"'><label >Price</label><input type='number' class='form-control' name='price[]' placeholder='Price' required='' /></li>");
            $("#fieldList").append("<li class='"+i+"'><label >Discount(<i class='fa fa-inr'></i>)</label><input type='number' class='form-control' name='discounts[]' placeholder='Discount'/></li>");
            $("#fieldList").append("<li class='"+i+"'><label >Validity for ad Quantity (Represents days)</label><input type='number'class='form-control' name='planvalitity[]' placeholder='Valitity Represent Days' required='' /></li>");

        });

        $("#removeMore").click(function(e) {
            e.preventDefault();

            if(i<=4){
                $("#addMore").show();
            }
            if(i>1){
                var classname='.'+i;
                i=i-1; 
                $(classname).remove();
                $('#detailscount').val(i);
            }
            if(i==1){
                $("#removeMore").hide();
            }
        });
    });
</script>
<script src="https://cdn.ckeditor.com/4.13.0/basic/ckeditor.js"></script>

<script>
    CKEDITOR.replace( 'comment' );
    $(document).ready(function() {
        $('#caategory').select2();
    });
</script>

@endsection