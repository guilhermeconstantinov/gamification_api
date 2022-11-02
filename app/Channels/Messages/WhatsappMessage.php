<?php
namespace App\Channels\Messages;

use Illuminate\Support\Facades\Http;

class WhatsappMessage {

    protected $to;
    protected $type;
    protected $contactName;
    protected $qrcodeLink;
    protected $luckyNumber;
    protected $dryrun;

    //Config
    private $namespace;
    private $positusToken;
    private $positusUrl;
    private $numberId;


    public function __construct()
    {
        $this->numberId = config('env.POSITUS_NUMBER_ID');
        $this->namespace = config('env.POSITUS_NAMESPACE');
        $this->positusToken = config('env.POSITUS_API_TOKEN');
        $this->positusUrl = config('env.POSITUS_URL');

        return $this;
    }

    public function type($type): self
    {
        $this->type = $type;

        return $this;
    }

    public function luckyNumber($luckyNumber): self
    {
        $this->luckyNumber = $luckyNumber;

        return $this;
    }

    public function contactName($contactName): self
    {
        $this->contactName = $contactName;

        return $this;
    }

    public function to($to): self
    {
        $this->to = $to;

        return $this;
    }

    public function qrcodeLink($qrcodeLink): self
    {
        $this->qrcodeLink = $qrcodeLink;

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function send()
    {
        if (!$this->to || !$this->type || !$this->contactName) {
            throw new \Exception("WhatsappChannel not correct.");
        }

        $components = null;

        if($this->type === 'checkin') {
            $components = $this->componentsWithHeaderAndBody();
            $templateName = 'brinde_imagem_001';
        }

        if($this->type === 'luckyNumber'){
            $components = $this->componentsWithBody();
            $templateName = 'game_sorteio';
        }

        if($this->type === 'raffle') {
            $components = $this->componentsWithHeaderAndBody2();
            $templateName = 'game_ganhador';
        }

        $request = [
            "to" => '+55'. $this->to,
            "type" => "template",
            "template" => [
                "namespace" => $this->namespace,
                "name" => $templateName,
                "language" => [
                    "policy" => "deterministic",
                    "code" => "pt_BR"
                ],
            ]
        ];

        if($components){
            $request['template']['components'] = $components;
        }

        $http = Http::withToken($this->positusToken)
            ->post("{$this->positusUrl}/v2/whatsapp/numbers/{$this->numberId}/messages", $request);

        return $http;
    }

    public function componentsWithHeaderAndBody(): array
    {
        return
            [
                [
                    "type" => "header",
                    "parameters" => [
                        [
                            "type" => "image",
                            "image" => [
                                "link" => $this->qrcodeLink
                            ]
                        ]
                    ]
                ],
                [
                    "type" => "body",
                    "parameters" => [
                        [
                            "type" => "text",
                            "text" => $this->contactName
                        ]
                    ]
                ],
            ];
    }

    public function componentsWithHeaderAndBody2(): array
    {
        return
            [
                [
                    "type" => "header",
                    "parameters" => [
                        [
                            "type" => "image",
                            "image" => [
                                "link" => $this->qrcodeLink
                            ]
                        ]
                    ]
                ],
                [
                    "type" => "body",
                    "parameters" => [
                        [
                            "type" => "text",
                            "text" => $this->contactName
                        ],
                        [
                            "type" => "text",
                            "text" => $this->luckyNumber
                        ]
                    ]
                ],
            ];
    }

    public function componentsWithBody(): array
    {
        return  [
            [
                "type" => "body",
                "parameters" => [
                    [
                        "type" => "text",
                        "text" => $this->contactName
                    ],
                    [
                        "type" => "text",
                        "text" => $this->luckyNumber
                    ]
                ]
            ]
        ];
    }

    public function dryrun($dry = 'yes'): self
    {
        $this->dryrun = $dry;

        return $this;
    }


}
