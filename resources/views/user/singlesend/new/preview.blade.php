<div class="card-body" style="justify-content: flex-end; text-align: right; background: url('{{ asset('assets/img/bg.png') }}');">
    <!--if templated Selected -->
    <div class="card" id="previewElement" style="text-align: left; border-top-left-radius: 0; margin-bottom: 5px;">
        <!-- foreach selected Template Components  -->
        <!-- if component type == HEADER && component format =="DOCUMENT"-->
        <div id="documentPrev">
        </div>
        <!-- endif -->
        <!-- if component type=="HEADER" && component format =="IMAGE" -->
        <div id ="imagePrev">
        </div>
        <!-- endif -->
        <!-- if component type =="HEADER" && component format =="VIDEO" -->
        <div id ="videoPrev">
        </div>
        <!-- endif -->
        <!-- endforeach -->
        <div class="card-body" style="">
             <!-- foreach selected Template Components  -->
              <!-- if component type == HEADER && component format =="TEXT"-->
            <h4 id="headertext" class="card-title mb-2"></h4>
            <!--if component type == BODY -->
            <p id="combody" class="card-text"></p>
             <!-- endif -->
             <!-- else if component type == FOOTER -->
            <span id="footerPrev" class="text-muted text-xs"></span>
            <!--endif -->
        <!-- endforeach -->
        </div>
    </div>
    <!-- foreach selected Template Components  -->
    <!--if component type == BUTTONS -->
    <!-- Foreach Component's Button -->
    <div id ="buttonsPrev">
    </div>
    </div>
     <!--endforeach -->
       <!--endif-->
        <!--endforeach-->
    <!--endif-->
</div>

<div class="col-sm-12" style="text-align:center;margin-bottom: 10px;">
        <button id="submitBtn" type="submit" class="btn btn-outline-primary submit-button">{{ __('Send Message') }}</button>
    </div>
