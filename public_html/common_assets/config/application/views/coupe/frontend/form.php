<div class="order_modal modal_wrapper">
	<div class="modal_content">
		<div class="modal_close"><i class="glyphicon glyphicon-remove"></i></div>
		<h2>Отправить заявку</h2>
		<form id="send_order">
			<div class="form_inputs">
				<div class="col-xs-12">
					<input data_required="text" placeholder="Имя*" type="text" name="name">
				</div>
				<div class="col-xs-12">
					<input data_required="email" placeholder="Email*" type="text" name="email">
				</div>
				<div class="col-xs-12">
					<input data_required="phone"  placeholder="Телефон*" type="text" name="phone">
				</div>
				<div class="col-xs-12">
					<textarea name="comments" placeholder="Комментарии" cols="30" rows="10"></textarea>
				</div>
				<div class="col-xs-12">
					<input type="checkbox" id="accept_data" name="accept_data">
					<label for="accept_data">Я согласен на обработку персональных данных</label>
				</div>
				<input type="hidden" name="screen">
				<input type="hidden" name="screen_cabinet">
				<input type="hidden" name="screen_doors">
				<input type="hidden" name="specs">
				<input type="hidden" name="pdf_file">
				<input id="save_data_form_input" type="hidden" name="save_data">
				<input type="submit" class="btn btn-success btn-sm">
				<input type="hidden" id="form_mats" name="form_mats">
			</div>
			<div class="form_success">
				<p>Спасибо за вашу заявку, наш менеджер свяжется с вами в ближайшее время</p>
			</div>
		</form>
	</div>
</div>