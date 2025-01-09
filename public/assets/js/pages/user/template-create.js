function showHideHeaderDiv() {
	var headerTypeSelect = document.getElementById('header_type');
	var textHeaderDiv = document.getElementById('text_header_div');
	var mediaHeaderDiv = document.getElementById('media_header_div');

	// Get the selected header type
	var selectedHeaderType = headerTypeSelect.value;

	// Hide/show div based on selected header type
	if (selectedHeaderType === 'text') {
		textHeaderDiv.style.display = 'block';
		mediaHeaderDiv.style.display = 'none';
	} else if (selectedHeaderType === 'media') {
		textHeaderDiv.style.display = 'none';
		mediaHeaderDiv.style.display = 'block';
	} else {
		textHeaderDiv.style.display = 'none';
		mediaHeaderDiv.style.display = 'none';
	}
}

// Event listener for header type select
var headerTypeSelect = document.getElementById('header_type');
headerTypeSelect.addEventListener('change', showHideHeaderDiv);

// Initial execution of showHideHeaderDiv function
showHideHeaderDiv();
// Function to add input fields for parameters in text header
function addParameterInputFields(numParameters) {
	var paramsContainer = document.getElementById('text_header_params');
	paramsContainer.innerHTML = ''; // Clear previous parameter input fields

	for (var i = 1; i <= numParameters; i++) {
		var parameterInput = document.createElement('input');
		parameterInput.type = 'text';
		parameterInput.name = 'text_parameter_' + i; // Unique name for text header parameter input
		parameterInput.id = 'parameter_input_' + i; // Unique id for parameter input
		parameterInput.placeholder = 'Parameter ' + i;
		parameterInput.classList.add('form-control');
		paramsContainer.appendChild(parameterInput);
	}
}

// Event listener for text header input
var textHeaderInput = document.getElementById('text_header');
textHeaderInput.addEventListener('input', function() {
	var headerValue = this.value;
	var numParameters = (headerValue.match(/\{\d+}/g) || []).length;
	addParameterInputFields(numParameters);
});

// Function to add input fields for parameters in message textarea
function addMessageParameterInputFields() {
	var messageTextarea = document.getElementById('message2');
	var messageValue = messageTextarea.value;
	var matches = messageValue.match(/\{\d+}/g);
	var parameterFieldsContainer = document.getElementById('parameter_fields');

	// Clear previous parameter input fields
	parameterFieldsContainer.innerHTML = '';

	if (matches && matches.length > 0) {
		for (var i = 0; i < matches.length; i++) {
			var parameterInput = document.createElement('input');
			parameterInput.type = 'text';
			parameterInput.name = 'message_parameter_' + (i + 1); // Unique name for message parameter input
			parameterInput.id = 'parameter_input_message_' + (i + 1); // Unique id for parameter input
			parameterInput.placeholder = 'Enter Content for {{'+ (i + 1) +'}}';
			parameterInput.classList.add('form-control');
			parameterInput.style.marginBottom = '5px';
			parameterFieldsContainer.appendChild(parameterInput);
		}
	}
}

// Event listener for message textarea
var messageTextarea = document.getElementById('message2');
messageTextarea.addEventListener('input', addMessageParameterInputFields);




var languageData = {
  'af': 'Afrikaans',
  'sq': 'Albanian',
  'ar': 'Arabic',
  'az': 'Azerbaijani',
  'bn': 'Bengali',
  'bg': 'Bulgarian',
  'ca': 'Catalan',
  'zh_CN': 'Chinese (CHN)',
  'zh_HK': 'Chinese (HKG)',
  'zh_TW': 'Chinese (TAI)',
  'hr': 'Croatian',
  'cs': 'Czech',
  'da': 'Danish',
  'nl': 'Dutch',
  'en': 'English',
  'en_GB': 'English (UK)',
  'en_US': 'English (US)',
  'et': 'Estonian',
  'fil': 'Filipino',
  'fi': 'Finnish',
  'fr': 'French',
  'ka': 'Georgian',
  'de': 'German',
  'el': 'Greek',
  'gu': 'Gujarati',
  'ha': 'Hausa',
  'he': 'Hebrew',
  'hi': 'Hindi',
  'hu': 'Hungarian',
  'id': 'Indonesian',
  'ga': 'Irish',
  'it': 'Italian',
  'ja': 'Japanese',
  'kn': 'Kannada',
  'kk': 'Kazakh',
  'rw_RW': 'Kinyarwanda',
  'ko': 'Korean',
  'ky_KG': 'Kyrgyz (Kyrgyzstan)',
  'lo': 'Lao',
  'lv': 'Latvian',
  'lt': 'Lithuanian',
  'mk': 'Macedonian',
  'ms': 'Malay',
  'ml': 'Malayalam',
  'mr': 'Marathi',
  'nb': 'Norwegian',
  'fa': 'Persian',
  'pl': 'Polish',
  'pt_BR': 'Portuguese (BR)',
  'pt_PT': 'Portuguese (POR)',
  'pa': 'Punjabi',
  'ro': 'Romanian',
  'ru': 'Russian',
  'sr': 'Serbian',
  'sk': 'Slovak',
  'sl': 'Slovenian',
  'es': 'Spanish',
  'es_AR': 'Spanish (ARG)',
  'es_ES': 'Spanish (SPA)',
  'es_MX': 'Spanish (MEX)',
  'sw': 'Swahili',
  'sv': 'Swedish',
  'ta': 'Tamil',
  'te': 'Telugu',
  'th': 'Thai',
  'tr': 'Turkish',
  'uk': 'Ukrainian',
  'ur': 'Urdu',
  'uz': 'Uzbek',
  'vi': 'Vietnamese',
  'zu': 'Zulu'
};

var selectElement = document.getElementById('language');

for (var code in languageData) {
  if (languageData.hasOwnProperty(code)) {
    var languageName = languageData[code];
    var option = document.createElement('option');
    option.value = code;
    option.textContent =  languageName ;
    selectElement.appendChild(option);
  }
}
