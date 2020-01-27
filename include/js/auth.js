/**
 * Method checks login window status
 * 
 * @param Popup
 *            window object
 * @param Handler
 *            login handler
 */
function check_login_status(Popup, Handler) {
	return (function() {
		if (Popup.closed) {
			Handler()
		} else {
			setTimeout(check_login_status(Popup, Handler), 100)
		}
	})
}

/**
 * Method calculates screen's center
 */ 
function screen_center_pos(PopUpWidth, PopUpHeight)
{
	var Width = $(window).width()

	var Height = $(window).height()

	return({
		x : (Width/2 - PopUpWidth/2) , 
		y : (Height/2 - PopUpHeight/2)
	})
}

/**
 * Open login pop up
 * 
 * @param URL
 *            Login url
 * @param Title
 *            Popup title
 * @param Handler
 *            OnLogin handler
 */
function open_popup(URL, Title, Handler) {
	return (function() {
		var PopupWidth = Math.min($(window).width(), 800)
		var PopupHeight = Math.min($(window).height(), 600)
		var Pos = screen_center_pos(PopupWidth, PopupHeight)
		var Params = "width=" + PopupWidth + ",height=" + PopupHeight + ",toolbar=0,scrollbars=0,status=0,resizable=0,location=0,menuBar=0,left=" + Pos.x + ",top=" + Pos.y

		var Popup = window.open(URL, Title, Params)
		setTimeout(check_login_status(Popup, Handler), 2000)
		Popup.focus()
	})
}

/**
 * Setup login pop up
 * 
 * @param $Button
 *            Trigger button
 * @param URL
 *            Login url
 * @param Title
 *            Popup title
 * @param Handler
 *            OnLogin handler
 */
function setup_social_media_login_popup($Button, URL, Title, Handler) {
	$Button.click(open_popup(URL, Title, Handler));
}