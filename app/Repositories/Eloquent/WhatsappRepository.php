<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\WhatsappRepositoryInterface;
use Illuminate\Support\Facades\Http;


class WhatsappRepository implements WhatsappRepositoryInterface
{
    protected $http;
    protected $numberId;
    protected $namespace;

    public function __construct()
    {
        $this->numberId ='0191117c-b8e7-449e-b2d2-64c30e30ae0c';
        $this->namespace = '6bfa7ae1_5dfd_4f27_927a_a4a922d73d2e';
    }

    public function sendMessage($number, $templateName, $components)
    {
        $number = preg_replace("/[()\s-]/", "", $number);
        return Http::async()->withToken(config('positus.api_token'))
            ->post("/v2/whatsapp/numbers/{$this->numberId}/messages",
            [
                "to" => '+55'.$number,
                "type" => "template",
                "template" => [
                    "namespace" => $this->namespace,
                    "name" => $templateName,
                    "language" => [
                        "policy" => "deterministic",
                        "code" => "pt_BR"
                    ],
                    "components" => $components
                ]
            ]
        );
    }

    public function registerMessage($contactName, $number, $qrcodeLink)
    {
        return $this->sendMessage($number,'gamefication_checkin',
            [
                [
                    "type" => "header",
                    "parameters" => [
                        [
                            "type" => "image",
                            "image" => [
                                "link" => $qrcodeLink
                            ]
                        ]
                    ]
                ],
                [
                    "type" => "body",
                    "parameters" => [
                        [
                            "type" => "text",
                            "text" => $contactName
                        ]
                    ]
                ],
            ]);
    }

    public function simulationMessage($contactName, $number, $qrcodeLink)
    {
        return $this->sendMessage($number,'gamefication_simulacao',
            [
                [
                    "type" => "header",
                    "parameters" => [
                        [
                            "type" => "image",
                            "image" => [
                                "link" => $qrcodeLink
                            ]
                        ]
                    ]
                ],
                [
                    "type" => "body",
                    "parameters" => [
                        [
                            "type" => "text",
                            "text" => $contactName
                        ]
                    ]
                ],
            ]);
    }

    public function luckyNumberMessage($contactName, $number, $luckyNumber)
    {
        return $this->sendMessage($number,'gamefication_sorteio',
            [
                [
                    "type" => "body",
                    "parameters" => [
                        [
                            "type" => "text",
                            "text" => $contactName
                        ],
                        [
                            "type" => "text",
                            "text" => $luckyNumber
                        ]
                    ]
                ]
            ]);
    }

    public function raffleMessage($contactName, $number, $qrcodeLink)
    {
        return $this->sendMessage($number,'gamefication_premiacao',
            [
                [
                    "type" => "header",
                    "parameters" => [
                        [
                            "type" => "image",
                            "image" => [
                                "link" => $qrcodeLink
                            ]
                        ]
                    ]
                ],
                [
                    "type" => "body",
                    "parameters" => [
                        [
                            "type" => "text",
                            "text" => $contactName
                        ]
                    ]
                ],
            ]);
    }
}
