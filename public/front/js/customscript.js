function validateNumber(inputElement,maxlen) {
	const sValue = inputElement.value;
	const nLen = maxlen; 
	if (sValue.length > nLen) {
		inputElement.value = sValue.substring(0, nLen);
	}
	inputElement.value = inputElement.value.replace(/\D/g, '');
	// const errorMessage = document.getElementById('contactmobileError');
	// if (inputElement.value.length < nLen) {
	// 	errorMessage.textContent = `Please enter a ${nLen}-digit mobile number.`;
	// } else {
	// 	errorMessage.textContent = '';
	// }
}