<?php

namespace App\Libraries;
use App\Traits\Cloud;
use GuzzleHttp\Client as GuzzleClient;
use Netflie\WhatsAppCloudApi\Http\GuzzleClientHandler;

class WhatsappLibrary
{
    use Cloud;
    protected $clientHandler;
    protected $client;

    public function __construct()
    {
        $this->clientHandler = new GuzzleClientHandler();
        $this->client = new GuzzleClient();
    }
    
    public function fetchProfile($phoneNumberId, $accessToken){
    $apiVersion = 'v21.0';
    $url = "https://graph.facebook.com/{$apiVersion}/{$phoneNumberId}/whatsapp_business_profile";

    try {
        $response = $this->client->get($url, [
            'headers' => [
                'Authorization' => "Bearer {$accessToken}",
            ],
            'query' => [
                'fields' => 'about,address,description,email,profile_picture_url,websites,vertical',
            ],
        ]);

        // Check if the request was successful (status code 200)
        if ($response->getStatusCode() == 200) {
            $result = json_decode($response->getBody(), true);
            return $result;
        } else {
            // Handle unexpected status code
            return ['error' => 'Unexpected status code: ' . $response->getStatusCode()];
        }

    } catch (\Exception $e) {
        // Handle Guzzle exceptions or any other exceptions that might occur
        return ['error' => $e->getMessage()];
    }
}


    public function updateProfile(array $profileData, $phoneNumberId, $accessToken)
    {
        $apiVersion = 'v21.0';
        $url = "https://graph.facebook.com/{$apiVersion}/{$phoneNumberId}/whatsapp_business_profile";

        $data = [
    "messaging_product" => $profileData['messaging_product'],
    "about" => $profileData['about'],
    "address" => $profileData['address'],
    "description" => $profileData['description'],
    "vertical" => $profileData['vertical'],
    "email" => $profileData['email'],
    "websites" => $profileData['websites'],
];

if(isset($profileData['profile_picture_handle'])){
    $data["profile_picture_handle"] = $profileData['profile_picture_handle'];
}


        $timeout = 10; // Set the desired timeout value in seconds

        $response = $this->clientHandler->postJsonData($url, $data, [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ], $timeout);

        return $response;
    }

    public function uploadProfilePicture($filePath, $appId, $accessToken)
    {
        $apiVersion = 'v21.0';
        $fileContent = file_get_contents($filePath);

        // Step 1: Upload the image
        $uploadUrl = "https://graph.facebook.com/{$apiVersion}/{$appId}/uploads";
        $response = $this->client->request('POST', $uploadUrl, [
            'query' => [
                'file_length' => filesize($filePath),
                'file_type' => mime_content_type($filePath),
                'access_token' => $accessToken,
            ],
        ]);

        $uploadSessionId = json_decode($response->getBody(), true)['id'];

        // Step 2: Save session ID and signature
        // You can save these values in the database or session as needed.

        // Step 3: Call POST with the session ID and image file
        $fileOffset = 0;
        $uploadSessionUrl = "https://graph.facebook.com/{$apiVersion}/{$uploadSessionId}";
        $response = $this->client->request('POST', $uploadSessionUrl, [
            'headers' => [
                'Authorization' => 'OAuth ' . $accessToken,
                'file_offset' => $fileOffset,
            ],
            'body' => $fileContent,
        ]);

        // Step 4: Save handle result
        $handleResult = json_decode($response->getBody(), true)['h'];
        // Save the handle result in the database or session as needed.

        return $handleResult;
    }

    public function createTemplate($templateRawData, $header, $body, $footer, $buttons, $accountId, $accessToken)
    {
        
        
        $client = new \GuzzleHttp\Client();
        $url = "https://graph.facebook.com/v21.0/{$accountId}/message_templates";
        $templateName = $templateRawData['template_name'];
        if (!strpos($templateName, '_')) {
            $convertedName = str_replace(' ', '_', $templateName);
        } else {
            $convertedName = $templateName;
        }
        $templateData = [
            "name" => $convertedName,
            "category" => $templateRawData['category'],
            "allow_category_change" => true,
            "language" => $templateRawData['language'],
            "components" => [
                $body,
            ],
        ];
        
       if (!empty($header)) {
    $templateData["components"][] = $header;
}

if (!empty($footer)) {
    $templateData["components"][] = $footer;
}

if (!empty($buttons)) {
    $templateData["components"][] = $buttons;
}

        
       //dd($templateData);

        $response = $client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => $templateData,
        ]);

        $responseData = json_decode($response->getBody(), true);
        $status_code = $response->getStatusCode();

        // Handle the API response as needed
        // ...
        
        return $status_code;
    }

    public function getHeaderTextComponent($templateRawData, $media = null)
{
    $headerType = $templateRawData['header_type'];
    $headerExample = [];

    foreach ($templateRawData as $key => $value) {
        if (strpos($key, 'text_parameter_') === 0) {
            $parameterIndex = substr($key, strlen('text_parameter_'));
            $headerExample[] = $value;
        }
    }

    $headerComponent = [
        "type" => "HEADER",
        "format" => $headerType,
    ];

    if ($headerType === "text") {
        $text = $templateRawData['text_header'];
        $headerComponent["text"] = $text;

        if (!empty($headerExample)) {
            $headerComponent["example"] = [
                "header_text" => $headerExample,
            ];
        }
    } elseif ($headerType === "media") {
        $format = $templateRawData['media_type']; // For MEDIA headers only
        $headerComponent["format"] = $format;

        if (!empty($media)) {
            $headerComponent["example"] = [
                "header_handle" => $media,
            ];
        }
    } elseif ($headerType === "none") {
        $headerComponent = [];
    } else {
        $headerComponent = [];
    }

    return $headerComponent;
}


    public function getBodyTextComponent($templateRawData)
    {
        $bodyText = $templateRawData['message'];
        $bodyExample = [];

        foreach ($templateRawData as $key => $value) {
            if (strpos($key, 'message_parameter_') === 0) {
                $parameterIndex = substr($key, strlen('message_parameter_'));
                $bodyExample[$parameterIndex] = $value;
            }
        }

        $bodyComponent = [
            "type" => "BODY",
            "text" => $bodyText,
        ];

        if (!empty($bodyExample)) {
            $bodyComponent["example"] = [
                "body_text" => [
                    array_values($bodyExample)
                ],
            ];
        }

        return $bodyComponent;
    }

    public function getFooterTextComponent($templateRawData)
    {
        $footerText = $templateRawData['footer_text'];

        $footerComponent = [];

        if ($footerText !== null) {
            $footerComponent = [
                "type" => "FOOTER",
                "text" => $footerText,
            ];
        }

        return $footerComponent;
    }

    public function getButtonsComponent($templateRawData)
    {
        $buttons = $templateRawData['buttons'];
        $buttonComponent = [];

        $formattedButtons = [];

        foreach ($buttons as $index => $button) {
            if ($button['type'] === "callButton") {
                $formattedButton = [
                    "type" => "PHONE_NUMBER",
                    "text" => $button['displaytext'],
                    "phone_number" => $button['action'],
                ];
            } elseif ($button['type'] === "urlButton") {
                $formattedButton = [
                    "type" => "URL",
                    "text" => $button['displaytext'],
                    "url" => $button['action'],
                ];

                if (isset($button['example'])) {
                    $formattedButton['example'] = [$button['example']];
                }
            } elseif ($button['type'] === "quickReplyButton") {
                $formattedButton = [
                    "type" => "QUICK_REPLY",
                    "text" => $button['displaytext'],
                ];
            }else{
                
                $formattedButton = [];
            }

            $formattedButtons[] = $formattedButton;
        }

        if (!empty($formattedButtons)) {
            $buttonComponent = [
                "type" => "BUTTONS",
                "buttons" => $formattedButtons,
            ];
        }else{
            $buttonComponent = [];
        }
        

        return $buttonComponent;
    }

    public function getTemplate($businessId, $accessToken)
    {
        $client = new \GuzzleHttp\Client();
        $url = "https://graph.facebook.com/v21.0/{$businessId}/message_templates";

        $response = $client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
        ]);

        $responseData = json_decode($response->getBody(), true);

        // Extract the desired fields from the response
        $templates = [];
        foreach ($responseData['data'] as $template) {
            $templates[] = [
                'name' => $template['name'],
                'status' => $template['status'],
                'id' => $template['id'],
            ];
        }

        return $templates;
    }

    public function editTemplate($header, $body, $footer, $buttons, $templateId, $accessToken)
    {
        $client = new \GuzzleHttp\Client();
        $url = "https://graph.facebook.com/v21.0/{$templateId}";
        $templateData = [$header, $body, $footer, $buttons];
        $response = $client->post($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => $templateData,
        ]);

        $responseData = json_decode($response->getBody(), true);
        return $responseData;
    }

    public function retrieveUrl($media_id, $accessToken){
        $client = new \GuzzleHttp\Client();
        $url = "https://graph.facebook.com/v21.0/{$media_id}";
        $response = $client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
        ]);
        
        // Check if the request was successful (status code 200)
        if ($response->getStatusCode() === 200) {
            // Parse the JSON response
            $responseData = json_decode($response->getBody(), true);
            
            // Check if the URL key exists in the response data
            if (isset($responseData['url'])) {
                // Return the retrieved URL
                $media = $responseData['url'];
                $mediaData = $client->get($media, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                    ],
                ]);
                if ($mediaData->getStatusCode() === 200){
                    $imageContent = $mediaData->getBody();
                    $contentType = $mediaData->getHeader('Content-Type')[0];

                    $extensionMap = [
                        'image/jpeg'=> 'jpg',
                        'image/png'=> 'png',
                        'audio/mpeg'=> 'mp3',
                        'video/mp4'=> 'mp4',
                        'audio/aac'=> 'aac',
                        'audio/amr'=> 'amr',
                        'audio/ogg'=> 'ogg',
                        'audio/mp4'=> 'mp4',
                        'text/plain'=> 'txt',
                        'application/pdf'=> 'pdf',
                        'application/vnd.ms-powerpoint'=> 'ppt',
                        'application/msword'=> 'doc',
                        'application/vnd.ms-excel'=> 'xls',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'=> 'docx',
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation'=> 'pptx',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'=> 'xlsx',
                        'video/3gp'=> '3gp',
                        'image/webp'=> 'webp'
                        
                        // Add more mappings as necessary
                    ];
                    $extension = $extensionMap[$contentType] ?? 'unknown';
                    $filename = 'media_' . uniqid() . '.' . $extension;
                    $storagePath = 'files/' . $filename;
                    \Storage::disk('public')->put($storagePath, $imageContent);
                    $mediaUrl = asset($storagePath);
                    return $mediaUrl;
                }
            }
        }
        
        // Return null or handle the error as needed
        return null;
    }
    
    // December 2023
    
    public function loadTemplatesFromWhatsApp($businessId, $accessToken, $after=""){
        $client = new \GuzzleHttp\Client();
        $url="https://graph.facebook.com/v21.0/{$businessId}/message_templates";
        $queryParams = [
            'fields' => 'name,category,language,quality_score,components,status',
            'limit' => 100
        ];
        if($after!=""){
            $queryParams['after']=$after;
        }

         $response = $client->get($url, [
    'headers' => [
        'Authorization' => 'Bearer ' . $accessToken,
    ],
    'query' => $queryParams,
]);

    

        // Handle the response here
        if ($response->getStatusCode() === 200) {
            $responseData = json_decode($response->getBody(), true);
            return $responseData;
        }else {
            // Handle error response
           return false;
        }
    }


    public function metaTemplatemessage($accountId, $accessToken, $message)
    {
       //dd($message);
        
        $client = new \GuzzleHttp\Client();
        $url = "https://graph.facebook.com/v21.0/{$accountId}/messages";
        
        $components = json_decode($message->updated_parameters);
        
        
if ($message->type && !empty($components) && count($components) > 0){
foreach ($components as $component) {
    // Check if the component type is 'HEADER' and has parameters
    if ($component->type === 'HEADER' && isset($component->parameters)) {
        // Iterate through parameters
        foreach ($component->parameters as $parameter) {
            // Check if the parameter type is 'image' and has a 'link' property
            if ($parameter->type === 'image' && isset($parameter->image->link)) {
                // Use the saveFile function to get the image link
                $imageLink = $this->saveFile($message, 'header_image');
                $parameter->image->link = $imageLink;
            }
            
            if ($parameter->type === 'document' && isset($parameter->document->link)){
                 $docLink = $this->saveFile($message, 'header_document');
                 $parameter->document->link = $docLink;
            }
            
            if ($parameter->type === 'video' && isset($parameter->video->link)) {
                // Use the saveFile function to get the image link
                $videoLink = $this->saveFile($message, 'header_video');
                $parameter->video->link = $videoLink;
            }
        }
    }
}
}else if($message->type && empty($components)){
    
    if($message->type == 'IMAGE'){
        $components = [
            [
                "type" => "HEADER",
                "parameters" => [
                    [
                        "type" => "image",
                        "image" => [
                            "link" => $message->image_example
                        ]
                    ]
                ]
            ]
        ];
    }else if($message->type == 'DOCUMENT'){
        $components = [
            [
                "type" => "HEADER",
                "parameters" => [
                    [
                        "type" => "document",
                        "document" => [
                            "link" => $message->doc_example
                        ]
                    ]
                ]
            ]
        ];
    }else if($message->type == 'VIDEO'){
        $components = [
            [
                "type" => "HEADER",
                "parameters" => [
                    [
                        "type" => "video",
                        "video" => [
                            "link" => $message->video_example
                        ]
                    ]
                ]
            ]
        ];
    }
}

            $requestData = [
        'messaging_product' => 'whatsapp',
        'to' => $message->phone,
        'type' => 'template',
        'template' => [
            'name' => $message->template_text,
            'language' => [
                'code' => $message->language ?? 'en_US',
            ],
        ],
    ];
    
    if (!empty($components) && count($components) > 0) {
        $requestData['template']['components'] = $components;
    }
    
    //dd($requestData);
    $response = $client->post($url, [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ],
        'json' => $requestData,
    ]);
    
    $statusCode = $response->getStatusCode();
        $content = json_decode($response->getBody(), true);
        
        return $content;

        // Handle the response as needed based on $statusCode and $content
    
}


    

}
