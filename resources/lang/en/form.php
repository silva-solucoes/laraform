<?php
$now = \Moment::now();

$short_answer_sub_template = <<<SUB_TEMPLATE
	<div class="panel template short-answer" id="short-answer-template" data-attribute-type="single">
		<div class="panel-body mb-10">
			<div class="form-group">
				<div class="mb-10">
					<input type="text" class="form-control input-xlg question-name" id="question-name" name="question-name" placeholder="Question" maxlength="255" minlength="3" required>
				</div>
				<input type="text" class="form-control" value="short answer" readonly>
			</div>
		</div>
		<div class="panel-footer panel-footer-bordered">
			<div class="heading-elements">
				<span class="heading-text text-semibold">Short Answer Question Type</span>
				<div class="pull-right">
					<div class="heading-form">
						<div class="form-group">
							<label class="checkbox-inline checkbox-right checkbox-switchery switchery-sm">
								<input type="checkbox" class="switchery question-required" name="question-required" id="question-required" checked="checked">
								Required
							</label>
						</div>
					</div>
					<button type="button" class="btn bg-danger-400 btn-xs heading-btn question-delete" data-form="short-answer-form" data-form-field="question-name">Delete</button>
				</div>
			</div>
		</div>
	</div>
SUB_TEMPLATE;

$short_answer_main_template = <<<MAIN_TEMPLATE
	<div class="row template short-answer">
		<div class="col-md-12">
			<div class="form-group">
				<label for="answer" class="label-xlg field-label"><span class="question">Short Answer Question:</span></label>
				<input type="text" class="form-control" id="answer" name="answer" placeholder="Answer" value="" maxlength="255" minlength="3">
			</div>
		</div>
	</div>
MAIN_TEMPLATE;

$long_answer_sub_template = <<<SUB_TEMPLATE
	<div class="panel template long-answer" id="long-answer-template" data-attribute-type="single">
		<div class="panel-body mb-10">
			<div class="form-group">
				<div class="mb-10">
					<input type="text" class="form-control input-xlg question-name" id="long-question-name" name="long-question-name" placeholder="Question" maxlength="255" minlength="3" required>
				</div>
				<input type="text" class="form-control" value="long answer" readonly>
			</div>
		</div>
		<div class="panel-footer panel-footer-bordered">
			<div class="heading-elements">
				<span class="heading-text text-semibold">Long Answer Question Type</span>
				<div class="pull-right">
					<div class="heading-form">
						<div class="form-group">
							<label class="checkbox-inline checkbox-right checkbox-switchery switchery-sm">
								<input type="checkbox" class="switchery question-required" name="long-question-required" id="long-question-required" checked="checked">
								Required
							</label>
						</div>
					</div>
					<button type="button" class="btn bg-danger-400 btn-xs heading-btn question-delete" data-form="long-answer-form" data-form-field="long-question-name">Delete</button>
				</div>
			</div>
		</div>
	</div>
SUB_TEMPLATE;

$long_answer_main_template = <<<MAIN_TEMPLATE
	<div class="row template long-answer">
		<div class="col-md-12">
			<div class="form-group">
				<label for="long-answer" class="label-xlg field-label"><span class="question">Long Answer Question:</span></label>
				<textarea rows="1" cols="5" class="form-control elastic" id="long-answer" name="long-answer" placeholder="Answer" maxlength="30000" data-rule-min-words="3"></textarea>
			</div>
		</div>
	</div>
MAIN_TEMPLATE;

$multiple_choices_sub_template = <<<SUB_TEMPLATE
	<div class="panel template multiple-choices" id="multiple-choices-template" data-attribute-type="multiple">
		<div class="panel-body mb-10">
			<div class="form-group mb-10">
				<input type="text" class="form-control input-xlg question-name" id="multiple-question-name" name="multiple-question-name" placeholder="Question" maxlength="255" minlength="3" required>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group mb-10">
						<div class="input-group">
							<span class="input-group-addon">
								<i class="icon-radio-checked"></i>
							</span>
							<input type="text" id="option-1" name="option-1" class="form-control question-option" placeholder="Option 1" maxlength="255" minlength="3" required>
							<span class="input-group-addon no-padding-bottom">
								<button type="button" class="btn btn-xs btn-default add-option">Add Option</button>
							</span>
						</div>
					</div>
					<div class="options-wrapper">
						<div class="form-group mb-10 hidden" id="hidden-option">
							<div class="input-group">
								<span class="input-group-addon">
									<i class="icon-radio-checked"></i>
								</span>
								<input type="text" id="option-disabled" class="form-control question-option" placeholder="Option" disabled>
								<span class="input-group-addon no-padding-bottom">
									<button type="button" class="btn btn-xs btn-default remove-option"><i class="icon-cross2"></i></button>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer panel-footer-bordered">
			<div class="heading-elements">
				<span class="heading-text text-semibold">Multiple Choices Question Type</span>
				<div class="pull-right">
					<div class="heading-form">
						<div class="form-group">
							<label class="checkbox-inline checkbox-right checkbox-switchery switchery-sm">
								<input type="checkbox" class="switchery question-required" name="multiple-question-required" id="multiple-question-required" checked="checked">
								Required
							</label>
						</div>
					</div>
					<button type="button" class="btn bg-danger-400 btn-xs heading-btn question-delete" data-form="multiple-choices-form" data-form-field="multiple-question-name">Delete</button>
				</div>
			</div>
		</div>
	</div>
SUB_TEMPLATE;

$multiple_choices_main_template = <<<MAIN_TEMPLATE
	<div class="row template multiple-choices">
		<div class="col-md-12">
			<div class="form-group">
				<label for="multiple-answer" class="label-xlg field-label"><span class="question">Multiple Choice Question:</span></label>
				<div class="options button radios">
					<div class="radio mt-15 mb-15 sample">
						<label class="option-label">
							<input type="radio" name="name" id="multiple-option-1" class="styled"> <span class="option">Option 1</span>
						</label>
					</div>
				</div>
		    </div>
	    </div>
    </div>
MAIN_TEMPLATE;

$checkboxes_sub_template = <<<SUB_TEMPLATE
	<div class="panel template checkboxes" id="checkboxes-template" data-attribute-type="multiple">
		<div class="panel-body mb-10">
			<div class="form-group mb-10">
				<input type="text" class="form-control input-xlg question-name" id="checkbox-question-name" name="checkbox-question-name" placeholder="Question" maxlength="255" minlength="3" required>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group mb-10">
						<div class="input-group">
							<span class="input-group-addon">
								<i class="icon-checkbox-checked"></i>
							</span>
							<input type="text" id="checkbox-option-1" name="checkbox-option-1" class="form-control question-option" placeholder="Option 1" maxlength="255" minlength="3" required>
							<span class="input-group-addon no-padding-bottom">
								<button type="button" class="btn btn-xs btn-default add-checkbox">Add Option</button>
							</span>
						</div>
					</div>
					<div class="checkbox-options-wrapper">
						<div class="form-group mb-10 hidden" id="hidden-checkbox-option">
							<div class="input-group">
								<span class="input-group-addon">
									<i class="icon-checkbox-checked"></i>
								</span>
								<input type="text" id="checkbox-option-disabled" class="form-control question-option" placeholder="Option" disabled>
								<span class="input-group-addon no-padding-bottom">
									<button type="button" class="btn btn-xs btn-default remove-checkbox"><i class="icon-cross2"></i></button>
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer panel-footer-bordered">
			<div class="heading-elements">
				<span class="heading-text text-semibold">Checkbox Question Type</span>
				<div class="pull-right">
					<div class="heading-form">
						<div class="form-group">
							<label class="checkbox-inline checkbox-right checkbox-switchery switchery-sm">
								<input type="checkbox" class="switchery question-required" name="checkbox-question-required" id="checkbox-question-required" checked="checked">
								Required
							</label>
						</div>
					</div>
					<button type="button" class="btn bg-danger-400 btn-xs heading-btn question-delete" data-form="checkboxes-form" data-form-field="checkbox-question-name">Delete</button>
				</div>
			</div>
		</div>
	</div>
SUB_TEMPLATE;

$checkboxes_main_template = <<<MAIN_TEMPLATE
	<div class="row template checkboxes">
		<div class="col-md-12">
			<div class="form-group">
				<label for="checkboxes-answer" class="label-xlg field-label"><span class="question">Checkbox Question:</span></label>
				<div class="checkboxes">
					<div class="checkbox">
						<label class="option-label">
							<input type="checkbox" name="name" id="checkbox-option-1" class="styled"> <span class="option">Option 1</span>
						</label>
					</div>
				</div>
			</div>
		</div>
	</div>
MAIN_TEMPLATE;
?>
