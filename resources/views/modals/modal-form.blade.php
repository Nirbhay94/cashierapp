<div class="modal fade" id="confirmForm" role="dialog" aria-labelledby="confirmFormLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">
          {{ trans('modals.form_modal_default_title') }}
        </h4>
      </div>
      <div class="modal-body">
        <p>
          {{ trans('modals.form_modal_default_message') }}
        </p>
      </div>
      <div class="modal-footer">
        {!! Form::button('<i class="fa fa-fw fa-close" aria-hidden="true"></i> ' . __('Cancel'), array('class' => 'btn btn-outline pull-left btn-flat', 'type' => 'button', 'data-dismiss' => 'modal' )) !!}
        {!! Form::button('<i class="fa fa-fw fa-check" aria-hidden="true"></i> ' . __('Submit'), array('class' => 'btn btn-outline btn-flat pull-right', 'type' => 'button', 'id' => 'confirm' )) !!}
      </div>
    </div>
  </div>
</div>