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
 <h5 class="heading_c ">Edit Classes</h5>
<div class="uk-width-xLarge-1-10">
    <div class="md-card">
        <div class="md-card-content" style="">
     
           
             <form  action=""  id="formn" accept-charset="utf-8" method="POST" name="applicationFormn"  v-form>
                <input type="hidden" name="_token" value="{!! csrf_token() !!}"> 
                  
                              
                  <div class="uk-grid" data-uk-grid-margin="">

                        <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                                
                               <label for="wizard_fullname">Class Name<span class="req uk-text-danger">*</span></label>
                                        <input type="text" name="name" value="{{$data->name}}"v-model='name' v-form-ctrl='' required class="md-input" />
                                          <p class="uk-text-danger uk-text-small"  v-if="applicationForm.name.$error.required" >Class name is required</p>

                            </div>
                        </div>
                         
                       
                         <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                               <label>Next Class<span class="req uk-text-danger">*</span></label>
                                <p></p>
                                        
                                <select placeholder='select next class' class="form-control" style="width:320px" name="next" required="required" class= 'md-input'v-model='next' v-form-ctrl='' v-select=''>
                                                    
                                                    @foreach($class as $item)

                                                   <option <?php
                                                                            if ($item==@$data->nextClass) {
                                                                                echo "selected='selected'";
                                                                            }
                                                                            ?> value="{{@$item}}"><?php echo @$item?></option>
                                                 @endforeach
                                                    </select> 
                                
                                
                                
                                
                                
                                <p class="uk-text-danger uk-text-small"  v-if="applicationForm.next.$error.required" >Next Class is required</p>

                            </div>
                        </div>

                        
                      <p>&nbsp;</p>
                         <div class="uk-width-medium-1-5">
                            <div class="uk-margin-small-top">
                               <label>Class Teacher<span class="req uk-text-danger">*</span></label>
                                <p></p>
                                        
                                <select placeholder='select class teacher' class="form-control" style="width:320px" name="teacher" required="required" >
                                            
                                                    @foreach($teacher as $items=>$dd)

                                                   <option <?php
                                                                            if ($item==@$data->teacherId) {
                                                                                echo "selected='selected'";
                                                                            }
                                                                            ?>  value="{{@$dd}}">{{@$dd}} </option>
                                                 @endforeach
                                                    </select> 
                                
                                
                                
                                
                                
                                <p class="uk-text-danger uk-text-small"  v-if="applicationForm.next.$error.required" >Next Class is required</p>

                            </div>
                        </div>

                         
                       
                        
                        
                    
                    </div> 

            <div class="  uk-text-right">
               <input type="submit" value="Update" id='save'  class="md-btn   md-btn-success uk-margin-small-top">
       <button type="button" class="md-btn md-btn-flat uk-modal-close md-btn-wave">Cancel</button>
            </div>
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
       UIkit.modal.alert('Updating class: {{$data->name}}');
         $(event.target).unbind("submit").submit();
    
                        
            });
            
    
                    
    
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