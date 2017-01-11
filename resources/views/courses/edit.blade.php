@extends('layouts.app')


@section('style')
<style>
    .md-card{
        width: auto;

    }
    
</style>
 <script src="{!! url('public/assets/js/jquery.min.js') !!}"></script>
 
        <script src="{!! url('public/assets/js/jquery-ui.min.js') !!}"></script>
 
@endsection
@section('content')
 <h5 class="heading_c ">Edit Courses</h5>
<div class="uk-width-xLarge-1-10">
    <div class="md-card">
        <div class="md-card-content" style="">

           
            <form  action=""  id="form" accept-charset="utf-8" method="POST" name="applicationForm"  v-form>
                <input type="hidden" name="_token" value="{!! csrf_token() !!}"> 
                  <div class="uk-grid">
                                    <div class="uk-width-small-1-2 parsley-row">
                                          <label>Program<span class="req uk-text-danger">*</span></label>
                                <p></p>
                                        
                                <select placeholder='select program' class="form-control" style="width:320px" name="program" required="required" class= 'md-input'v-model='program' v-form-ctrl='' v-select=''>
                                                    
                                                    @foreach($program as $item=>$rows)

                                                   <option <?php
                                                                            if ($rows->code==$data->pcode) {
                                                                                echo "selected='selected'";
                                                                            }
                                                                            ?> value="{{$rows->code}}">{{$rows->name}} </option>
                                                 @endforeach
                                                    </select> 
                                
                                
                                
                                
                                
                                <p class="uk-text-danger uk-text-small"  v-if="applicationForm.program.$error.required" >Program is required</p>

                                    </div>
                                </div>
              <div class="uk-grid">
                                    <div class="uk-width-small-1-2 parsley-row">
                                        <label for="wizard_fullname">Course Name<span class="req uk-text-danger">*</span></label>
                                        <input type="text" name="name" value="{{$data->name}}"v-model='name' v-form-ctrl='' required class="md-input" />
                                          <p class="uk-text-danger uk-text-small"  v-if="applicationForm.name.$error.required" >Course Name is required</p>

                                    </div>
                                </div>
                 <div class="uk-grid">
                                    <div class="uk-width-small-1-2 parsley-row">
                                        <label for="wizard_fullname">Course Code<span class="req uk-text-danger">*</span></label>
                                        <input type="text" name="code" value="{{$data->code}}"v-model='code' v-form-ctrl='' required class="md-input" />
                                          <p class="uk-text-danger uk-text-small"  v-if="applicationForm.code.$error.required" >Course Code is required</p>

                                    </div>
                                </div>
                              
                              
                
                
                
                
      <table align="center">
       
        <tr><td><input type="submit" value="Save" id='save'v-show="applicationForm.$valid"  class="md-btn   md-btn-success uk-margin-small-top">
      <input type="reset" value="Clear" class="md-btn   md-btn-default uk-margin-small-top">
    </td></tr></table>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
        $(document).ready(function(){
            $("#form").on("submit",function(event){
                event.preventDefault();
       UIkit.modal.alert('Updating Course: {{$data->name}}');
         $(event.target).unbind("submit").submit();
    
                        
            });
            
    
                    
    
    });
</script>
<script src="{!! url('public/assets/js/select2.full.min.js') !!}"></script>
  <script>
$(document).ready(function(){
  $('select').select2({ width: "resolve" });

  
});


</script>   
<script>


//code for ensuring vuejs can work with select2 select boxes
Vue.directive('select', {
  twoWay: true,
  priority: 1000,
  params: [ 'options'],
  bind: function () {
    var self = this
    $(this.el)
      .select2({
        data: this.params.options,
         width: "resolve"
      })
      .on('change', function () {
        self.vm.$set(this.name,this.value)
        Vue.set(self.vm.$data,this.name,this.value)
      })
  },
  update: function (newValue,oldValue) {
    $(this.el).val(newValue).trigger('change')
  },
  unbind: function () {
    $(this.el).off().select2('destroy')
  }
})


var vm = new Vue({
  el: "body",
  ready : function() {
  },
 data : {
   
   
 options: [    ]  
    
  },
   
})

</script>
@endsection    