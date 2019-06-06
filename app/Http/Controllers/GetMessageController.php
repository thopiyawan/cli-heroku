<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use LINE\LINEBot\HTTPClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;

use LINE\LINEBot\MessageBuilder;

use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;

define('LINE_MESSAGE_CHANNEL_SECRET','572a7adea7a0959295e21cb626dae011');
define('LINE_MESSAGE_ACCESS_TOKEN','UNACfqO1IKjufn4f6OfHZPuXnlKXsQBXgSd0Vl7uE+O2YuDAlk1obk36GW9D6WtGtLA952UKN+WigumQWopa81HhPgeoreDOyw+MOjdcQi6l3MeZt++4skzCQ8zdtmbIyCY/15AZOWKxKw1VGdep0wdB04t89/1O/w1cDnyilFU=');


class GetMessageController extends Controller
{
    public function getmessage() {    
        $content = file_get_contents('php://input');
         
        // แปลงข้อความรูปแบบ JSON  ให้อยู่ในโครงสร้างตัวแปร array
        $events = json_decode($content, true);
        if(!is_null($events)){
            // ถ้ามีค่า สร้างตัวแปรเก็บ replyToken ไว้ใช้งาน
            $replyToken = $events['events'][0]['replyToken'];
            $typeMessage = $events['events'][0]['message']['type'];
            $userMessage = $events['events'][0]['message']['text'];
            $userMessage = strtolower($userMessage);
            switch ($typeMessage){
                case 'text':
                    switch ($userMessage) {
                        case "t":
                            $textReplyMessage = "Bot ตอบกลับคุณเป็นข้อความ";
                            $replyData = new TextMessageBuilder($textReplyMessage);
                            break;
                        case "i":
                            $picFullSize = 'https://www.mywebsite.com/imgsrc/photos/f/simpleflower';
                            $picThumbnail = 'https://www.mywebsite.com/imgsrc/photos/f/simpleflower/240';
                            $replyData = new ImageMessageBuilder($picFullSize,$picThumbnail);
                            break;
                        case "v":
                            $picThumbnail = 'https://www.mywebsite.com/imgsrc/photos/f/sampleimage/240';
                            $videoUrl = "https://www.mywebsite.com/simplevideo.mp4";            	
                            $replyData = new VideoMessageBuilder($videoUrl,$picThumbnail);
                            break;
                        case "a":
                            $audioUrl = "https://www.mywebsite.com/simpleaudio.mp3";
                            $replyData = new AudioMessageBuilder($audioUrl,27000);
                            break;
                        case "l":
                            $placeName = "ที่ตั้งร้าน";
                            $placeAddress = "แขวง พลับพลา เขต วังทองหลาง กรุงเทพมหานคร ประเทศไทย";
                            $latitude = 13.780401863217657;
                            $longitude = 100.61141967773438;
                            $replyData = new LocationMessageBuilder($placeName, $placeAddress, $latitude ,$longitude);          	
                            break;
                        case "s":
                            $stickerID = 22;
                            $packageID = 2;
                            $replyData = new StickerMessageBuilder($packageID,$stickerID);
                            break;  	
                        case "im":
                            $imageMapUrl = 'https://www.mywebsite.com/imgsrc/photos/w/sampleimagemap';
                            $replyData = new ImagemapMessageBuilder(
                                $imageMapUrl,
                                'This is Title',
                                new BaseSizeBuilder(699,1040),
                                array(
                                    new ImagemapMessageActionBuilder(
                                        'test image map',
                                        new AreaBuilder(0,0,520,699)
                                        ),
                                    new ImagemapUriActionBuilder(
                                        'http://www.ninenik.com',
                                        new AreaBuilder(520,0,520,699)
                                        )
                                ));
                            break;      	
                        case "tm":
                            $replyData = new TemplateMessageBuilder('Confirm Template',
                                new ConfirmTemplateBuilder(
                                        'Confirm template builder',
                                        array(
                                            new MessageTemplateActionBuilder(
                                                'Yes',
                                                'Text Yes'
                                            ),
                                            new MessageTemplateActionBuilder(
                                                'No',
                                                'Text NO'
                                            )
                                        )
                                )
                            );
                            break;                                                                                                                      	
                        default:
                            $textReplyMessage = " คุณไม่ได้พิมพ์ ค่า ตามที่กำหนด";
                            $replyData = new TextMessageBuilder($textReplyMessage);     	
                            break;                                  	
                    }
                    break;
                default:
                    $textReplyMessage = json_encode($events);
                    $replyData = new TextMessageBuilder($textReplyMessage);     	
                    break; 
            }
        }
        //l ส่วนของคำสั่งตอบกลับข้อความ
        $response = $bot->replyMessage($replyToken,$replyData);
        
        }
        
}
