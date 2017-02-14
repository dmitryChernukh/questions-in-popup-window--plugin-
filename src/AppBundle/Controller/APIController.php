<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 28.01.16
 * Time: 17:42
 */
namespace AppBundle\Controller;
use AppBundle\Entity\Guest;
use AppBundle\Entity\Rating;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Doctrine\ORM\Mapping as ORM;
use \Doctrine\ORM\Query;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Validator\Constraints\DateTime;

class APIController extends BaseAdminController{

    const UPPER_LEFT = 'UL';
    const UPPER_RIGHT = 'UR';
    const LOWER_LEFT = 'LL';
    const LOWER_RIGHT = 'LR';
    const CENTER_SCREEN = 'CS';

    public function __construct()
    {

    }

    public function processingAction(Request $request){

        $response = new Response();
        $enabled = false;
        if ($request->getMethod() == Request::METHOD_POST) {
            $container = array();

            $content = json_decode($request->getContent(), true);

            $fullAddress = $content['Data']['fullAddress'];
            $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

            if(isset($content['Data']['siteUrl'])){

                if(isset($content['Data']['testMode']) && !empty($content['Data']['testMode'])){
                    if($content['Data']['testMode'] == true){
                        $enabled = true;
                    }
                }

                $urlContent = explode("//", $content['Data']['siteUrl']);
                $parameters = array('siteUrl' => $urlContent[1]);

                $site = $this->getDoctrine()
                    ->getRepository('AppBundle:Site')
                    ->createQueryBuilder('s')
                    ->select('s.id, IDENTITY(s.popUp) AS popUpId, IDENTITY(s.question) AS questionId, s.isSleep, s.subsite, s.appearance, s.attachedElement, s.attachedPopupId, s.appValue, s.enabled, s.note, s.siteUrl, s.name, p.name as positionName, p.abbreviation as positionAbbreviation')
                    ->join('s.popUpPosition', 'p')
                    ->where('s.siteUrl LIKE :siteUrl');
                    if($enabled == false){
                        $site->andWhere('s.enabled = :enabled')
                        ->andWhere('s.protocol = :protocol');
                        $parameters = array('siteUrl' => '%'.$urlContent[1].'%', 'enabled' => true, 'protocol' => $urlContent[0].'//');
                    }

                $site = $site->setParameters($parameters)
                    ->getQuery()
                    ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

                $box = array();
                if(!empty($site)){
                    foreach ($site as $el){
                        $p = strpos($fullAddress, $el['siteUrl']);
                        if ($p === false){
                            continue;
                        } else {
                            $box [] = $el;
                        }
                    }
                    $site = $box;
                }


                $em = $this->getDoctrine()->getManager();
                $entity = $em->getRepository('AppBundle:Guest')->findOneBy(array('ip_address' => $_SERVER['REMOTE_ADDR']));

                if ($entity == null)
                {
                    $guest = new Guest();
                    $guest->setIpAddress($_SERVER['REMOTE_ADDR']);
                    $guest->setBrowserName($content['Data']['browserName']);
                    $guest->setBrowserCodeName($content['Data']['browserCodeName']);
                    $guest->setLastConnect(date('Y-m-d H:i:s'));
                    $guest->setEnabled(true);
                    $guest->setLocalIdentifire($content['Data']['localIdentifier']);
                    $em->persist($guest);
                    $em->flush();
                }
                else
                {
                    $Guest = $this->getDoctrine()
                        ->getRepository('AppBundle:Guest')
                        ->createQueryBuilder('g')
                        ->select('g')
                        ->where('g.ip_address = :address')
                        ->setParameter('address', $_SERVER['REMOTE_ADDR'])
                        ->getQuery()
                        ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

                    $entity->setLastConnect(date('Y-m-d H:i:s'));
                    $entity->setLocalIdentifire($content['Data']['localIdentifier']);
                    $em->merge($entity);
                    $em->flush();

                    if($Guest[0]['enabled'] === false){
                        exit;
                    }
                }

                $container['popUp'] = [];
                $container['checker'] = 'ACCESS_CLOSE';

                $siteAdditionalValue = array();
                foreach($site as $element){

                    if($element['attachedElement'] == null){

                        $question = $this->getQuestion($element['questionId']);
                        $answers = $this->getAnswers($element['questionId']);

                        if($element['enabled'] == true || $enabled == true){

                            if($element['isSleep'] == false){
                                $container['popUp'] = $this->getPopUp($element['popUpId'], $baseurl, array($element), $question, $answers);
                                $container['CSSFiles'] = $this->getCSSFiles($element['popUpId']);
                                $container['checker'] = 'ACCESS_ALLOWED';
                                $element['appearance'] == null ? $container['appearance'] = 'SI' : $container['appearance'] = $element['appearance'];
                                $container['appValue'] = $element['appValue'];

                                $siteAdditionalValue = $this->getDoctrine()
                                    ->getRepository('AppBundle:AdditionalDisplayConditions')
                                    ->createQueryBuilder('a')
                                    ->select('a')
                                    ->where('a.siteId = :id')
                                    ->setParameter('id', $element['id'])
                                    ->getQuery()
                                    ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

                                break;
                            }
                        }

                    } else if(($element['attachedElement'] != null)){
                       if($element['enabled'] == true OR $enabled == true){
                           if($element['isSleep'] == false) {
                               $string = $content['Data']['fullAddress'];

                               $linkResult = false;
                               if(!empty($element['subsite'])){
                                   $linkResult = stristr($string, $element['subsite']);
                               }

                               if($linkResult !== FALSE) {
                                   $deepFile = array($element);
                                   $container['CSSFiles'] = $this->getCSSFilesInSite($deepFile);
                                   if ($element['attachedElement'] == 'RP') {
                                       $container['popUp'] = $this->getResultPopUp($baseurl, $element['attachedPopupId'], array($element));
                                   } else if ($element['attachedElement'] == 'UP') {
                                       $container['popUp'] = $this->getUserPopUp($baseurl, $element['attachedPopupId'], array($element));
                                   }
                                   $container['checker'] = 'ACCESS_ALLOWED';
                                   $element['appearance'] == null ? $container['appearance'] = 'SI' : $container['appearance'] = $element['appearance'];
                                   $container['appValue'] = $element['appValue'];
                               }
                           }
                       }
                    }
                }

                $container['siteAdditionalValue'] = isset($siteAdditionalValue[0]) ? $siteAdditionalValue[0] : $siteAdditionalValue;

                $response->setContent(json_encode($container));
            }
        }

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', "Content-Type, Origin, Accept, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        return $response;
    }

    public function getPreviewPopUpAction(Request $request){
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        $data = $request->request->all();
        $container['CSSFiles'] = array();
        $response = new Response();

        if($data['index'] == 'RP'){
            $container['CSSFiles'] = $this->getCSSFilesById($data['id']);
            $container['popUp'] = $this->getResultPopUp($baseurl, $data['id'], null);
        } else if($data['index'] == 'UP'){
            $container['CSSFiles'] = $this->getCSSFilesById($data['id']);
            $container['popUp'] = $this->getUserPopUp($baseurl, $data['id'], null);
        }

        $container['checker'] = 'ACCESS_ALLOWED';
        $container['appearance'] = 'SI';
        $container['appValue'] = null;
        $response->setContent(json_encode($container));

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


    public function previewModeAction(Request $request){
        $sort = [];
        $response = new Response();
        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        if ($request->getMethod() == Request::METHOD_POST) {
            $content = json_decode($request->getContent(), true);

            $urlContent = explode("//", $content['Data']['siteUrl']);
            $site = $this->getDoctrine()
                ->getRepository('AppBundle:Site')
                ->createQueryBuilder('s')
                ->select('s.id, IDENTITY(s.popUp) AS popUpId, IDENTITY(s.question) AS questionId, s.attachedElement, s.attachedPopupId, s.subsite, s.appearance, s.appValue, s.enabled, s.note, s.siteUrl, s.name, p.name as positionName, p.abbreviation as positionAbbreviation')
                ->join('s.popUpPosition', 'p')
                ->where('s.siteUrl = :siteUrl')
                ->andWhere('s.protocol = :protocol');
                $parameters = array('siteUrl' => $urlContent[1], 'protocol' => $urlContent[0].'//');
           $newSite = $site->setParameters($parameters)
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            $question = array();
            $answers = array();
            $container['CSSFiles'] = array();

            if(isset($content['Data']['popupId']) && !empty($content['Data']['popupId'])){
                $questions = $this->getDoctrine()
                    ->getRepository('AppBundle:Question')
                    ->createQueryBuilder('q')
                    ->select('q')
                    ->where('q.popUpID = :id')
                    ->setParameter('id', $content['Data']['popupId'])
                    ->getQuery()
                    ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

                foreach($questions as $question ){
                    $sort [] = $question['id'];
                }

                $answers = [];
                if(isset($sort) && !empty($sort)){
                    $question = $this->getQuestion(min($sort));
                    $answers = $this->getAnswers(min($sort));
                } else{
                    $question = array(0=>null);
                }
                $container['CSSFiles'] = $this->getCSSFiles($content['Data']['popupId']);
                $container['popUp'] = $this->getPopUp($content['Data']['popupId'], $baseurl, null, $question, $answers);
            }

            $switcher = null;
            if(isset($newSite) && !empty($newSite)) {
                foreach ($newSite as $element) {

                    if($element['attachedElement'] == 'RP'){
                        $deepFile = array($element);
                        $container['CSSFiles'] = $this->getCSSFilesInSite($deepFile);
                        $container['popUp'] = $this->getResultPopUp($baseurl, $element['attachedPopupId'], null);
                    } else if($element['attachedElement'] == 'UP'){
                        $deepFile = array($element);
                        $container['CSSFiles'] = $this->getCSSFilesInSite($deepFile);
                        $container['popUp'] = $this->getUserPopUp($baseurl, $element['attachedPopupId'], null);
                    } else if ($element['attachedElement'] == null){
                        if(isset($content['Data']['popupId'])){
                            $container['popUp'] = $this->getPopUp($content['Data']['popupId'], $baseurl, null, $question, $answers);
                            break;
                        } else {
                            continue;
                        }
                    }
                }
            }

            $container['checker'] = 'ACCESS_ALLOWED';
            $container['appearance'] = 'SI';
            $container['appValue'] = null;

            $response->setContent(json_encode($container));
        }
            $response->headers->set('Content-Type', 'application/json');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', "Content-Type, Origin, Accept, Access-Control-Allow-Headers, Authorization, X-Requested-With");
            return $response;
    }

    public function getCSSFiles($id){
        $files = array();
        $usersPopUp = $this->getDoctrine()
            ->getRepository('AppBundle:UserPopUp')
            ->createQueryBuilder('u')
            ->select('u')
            ->where('u.popupId = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        foreach($usersPopUp as $elements){
            if(!empty($elements ['cssFile']) && isset($elements ['cssFile']) ){
                $files [] =  $elements ['cssFile'];
            }
        }
        return $files;
    }

    public function getCSSFilesInSite($site){
        $files = array();
        if(isset($site[0]['attachedElement']) && $site[0]['attachedElement'] == 'UP'){
            $usersPopUp = $this->getDoctrine()
                ->getRepository('AppBundle:UserPopUp')
                ->createQueryBuilder('u')
                ->select('u')
                ->where('u.id = :id')
                ->setParameter('id', $site[0]['attachedPopupId'])
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            foreach($usersPopUp as $elements){
                if(!empty($elements ['cssFile']) && isset($elements ['cssFile']) ){
                    $files [] =  $elements ['cssFile'];
                }
            }
        }
        return $files;
    }

    public function getCSSFilesById($id){
        $files = array();
        $usersPopUp = $this->getDoctrine()
            ->getRepository('AppBundle:UserPopUp')
            ->createQueryBuilder('u')
            ->select('u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        foreach($usersPopUp as $elements){
            if(!empty($elements ['cssFile']) && isset($elements ['cssFile']) ){
                $files [] =  $elements ['cssFile'];
            }
        }
        return $files;
    }

    public function ratingAction(Request $request){

        $response = new Response();
        $content = json_decode($request->getContent(), true);
        if ($request->getMethod() == Request::METHOD_POST) {

            $em = $this->getDoctrine()->getManager();
            $rating = $em->getRepository('AppBundle:Rating')->findOneBy(array('guest_ip' => $_SERVER['REMOTE_ADDR'], 'site_url' => $content['Data']['siteUrl'], 'pop_up_id' => $content['Data']['pop_up_id'] ));

            if ($rating == null)
            {
                $rat = new Rating();
                $rat->setSiteUrl($content['Data']['siteUrl']);
                $rat->setGuestIp($_SERVER['REMOTE_ADDR']);
                $rat->setPopUpId($content['Data']['pop_up_id']);
                if($content['Data']['index'] == 'R1'){
                    $rat->setRatingOne($content['Data']['number']);
                } else if ($content['Data']['index'] == 'R2'){
                    $rat->setRatingTwo($content['Data']['number']);
                }
                $rat->setResponseTime(date('Y-m-d H:i:s'));
                $rat->setQuestionText($content['Data']['question_text']);
                $em->persist($rat);
                $em->flush();
            }
            else
            {
                if($content['Data']['index'] == 'R1'){
                    $rating->setRatingOne($content['Data']['number']);
                } else if ($content['Data']['index'] == 'R2'){
                    $rating->setRatingTwo($content['Data']['number']);
                }
                $rating->setResponseTime(date('Y-m-d H:i:s'));
                $rating->setQuestionText($content['Data']['question_text']);

                $em->merge($rating);
                $em->flush();
            }
        }

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', "Content-Type, Origin, Accept, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        return $response;
    }

    public function coordinateAction(Request $request){

        $response = new Response();

        if ($request->getMethod() == Request::METHOD_POST) {
            $content = json_decode($request->getContent(), true);
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Guest')->findOneBy(
                array(
                    'ip_address' => $_SERVER['REMOTE_ADDR']
                )
            );
            if (!$entity) {
                throw $this->createNotFoundException('No ip found for address '.$_SERVER['REMOTE_ADDR']);
            }

            $entity->setLatitude($content['Data']['latitude']);
            $entity->setLongitude($content['Data']['longitude']);
            $entity->setAccuracy($content['Data']['accuracy']);

            $em->merge($entity);
            $em->flush();

            $response->setContent(json_encode(array('success')));
        }

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', "Content-Type, Origin, Accept, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        return $response;
    }

    protected function getAnswers($id){
        $answers = $this->getDoctrine()
            ->getRepository('AppBundle:Answers')
            ->createQueryBuilder('a')
            ->select('a')
            ->where('a.question = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        return $answers;
    }

    protected function getQuestion($id){
        $question = $this->getDoctrine()
            ->getRepository('AppBundle:Question')
            ->createQueryBuilder('q')
            ->select('q')
            ->where('q.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        return $question;
    }

    protected function getLink($id){
        $link = $this->getDoctrine()
            ->getRepository('AppBundle:Links')
            ->createQueryBuilder('l')
            ->select('l')
            ->where('l.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        return $link;
    }

    protected function getPopUp($id, $baseurl, $site, $question, $answers){

        $popUp = $this->getDoctrine()
            ->getRepository('AppBundle:PopUp')
            ->createQueryBuilder('p')
            ->select('p,r')
            ->join('p.borderStyle', 'r')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);



        if (!$popUp) {
            throw $this->createNotFoundException(
                'No popUp found for id '.$id
            );
        } else{
            $pVal = $popUp[0];
            if(isset($site) && !empty($site) && $site != null){
                $position = $this->getPosition($site);
            } else {
                $position = 'bottom: 20px; right: 20px;';
            }

            if($pVal['archive'] === true){
                echo "The popup window is archived";
                exit;
            }

            $style = array(
                // in this place we can customize the style by each class
                'pop-up'            => "box-shadow: 0 0 10px rgba(0,0,0,0.5); font-family: \"Helvetica Neue\",Helvetica,Arial,sans-serif; z-index: 999; height: ".$pVal['height']."px; width: ".$pVal['width']."px; background-color: ".$pVal['bgColor']."; color: ".$pVal['textColor'].
                                       ";border-radius: ".$pVal['borderRadius']."px; border: ".$pVal['borderWidth']."px ".$pVal['borderStyle']['type']." ".$pVal['borderColour'].";
                                       position: fixed; ".$position,
                'button-answer'     => " word-wrap: break-word; text-align: ".$pVal['buttonTextAlign']."; white-space: normal; line-height: normal; display: block; color: ".$pVal['buttonTextColor']."; font-size: ".$pVal['answersTextSize']."pt; margin: ".$pVal['buttonSpace']."px auto; background-color: ".$pVal['buttonColor']."; border: 1px solid ".$pVal['buttonColorBorder']."; border-radius: ".$pVal['buttonBorderRadius']."px; width: 96%; cursor: pointer",
                'image-container'   => "width:100%;",
                'answer-container'  => "margin-top: ".$pVal['answersContainerMarginTop']."px; width:100%;",
                'header-image'      => "margin: 0 auto; display: block; width: ".$pVal['pictureWidth']."px; margin-top: ".$pVal['pictureTopMargin']."px; opacity: ".$pVal['pictureOpacity']."; border-radius: ".$pVal['pictureBorderRadius']."px;",
                'main-question'     => "font-size: ".$pVal['mainQuestionTextSize']."pt; text-align: center;
                                        line-height: ".$pVal['lineHeight']."; word-wrap: break-word; margin-top: ".$pVal['textTopMargin']."px; letter-spacing: ".$pVal['letterSpacing']."; font-style: ".$pVal['fontStyle']."; font-weight: ".$pVal['fontWeight'].";",
                'image'             => $pVal['image'],
                'close-pop-up'      => "cursor: pointer; margin-right: 8px; margin-top: 8px; margin-bottom: -18px; max-width: 18px; max-height: 18px; position: absolute; right: 0px;"
            );

        $carcass = "
            <div class='pop-up' id='main-pop-up-container' style='".$style['pop-up']."'>
                <div><img class='close-pop-up' id='close-popup-window' style='".$style['close-pop-up']."' src='".$baseurl."/app/system_image/close-button.png'></div>";

            if(isset($style['image']) && !empty($style['image'])){
                $carcass .=  "<div class='image-container' style='".$style['image-container']."'>
                <img class='header-image' style='".$style['header-image']."' src='".$baseurl."/uploads/images/".$style['image']."'>
                </div>";
            }

            $carcass .= "<div class='main-question' style='".$style['main-question']."'>".$question[0]['question']."</div>
                <div class='answer-container' style='".$style['answer-container']."'>";

            foreach($answers as $answer){
                $carcass.="<input type='button' class='button-answer' style='".$style['button-answer']."' key='".$answer['identifire']."' significance='".$answer['identifireStepID']."' value='".htmlspecialchars($answer['answer'], ENT_QUOTES)."'>";
            }

        $carcass .="</div> </div> ";
        }
        return $carcass;
    }

    protected function generateRandomString($length = 5) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    public function getActivePopUpAction(Request $request)
    {
        $response = new Response();
        $site = $this->getDoctrine()
            ->getRepository('AppBundle:Site')
            ->createQueryBuilder('s')
            ->select('s.id, IDENTITY(s.popUp) AS popUpId, IDENTITY(s.question) AS questionId, s.enabled, s.isSleep, s.note, s.siteUrl, s.attachedElement, s.name')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $responseBlock = array();

        if (count($site) > 0) {
            foreach ($site as $block) {
                if ($block['popUpId'] == null) {
                    continue;
                } else {
                    $responseBlock [] = $block;
                }
            }
        }
        return $response->setContent(json_encode($responseBlock));
    }

    protected function testFunction(){
        for ($i=1; $i<=100; $i++)
        {
            $s=socket_create(AF_INET, SOCK_STREAM, 0);
            $res=@socket_connect($s,  "127.0.0.1", $i);
            if ($res)
            {
                $portname=getservbyport($i, "tcp");
                print("<P> Port is open $i ($portname)").PHP_EOL;
            }
        }
    }

    public function getRating($ratingText, $style, $randomInt, $containerIndex, $marginBlock){
        return '<div class="rating-container" style="'.$containerIndex.'"><span id="" style="'.$style.'">'.$ratingText.'</span>
                         <div class="reviewStars-input" style="margin-top: '.$marginBlock.'%">
                         <input id="'.$randomInt.'-star-4" type="radio" name="'.$randomInt.'"/>
                         <label title="gorgeous" for="'.$randomInt.'-star-4"></label>
                         <input id="'.$randomInt.'-star-3" type="radio" name="'.$randomInt.'"/>
                         <label title="good" for="'.$randomInt.'-star-3"></label>
                         <input id="'.$randomInt.'-star-2" type="radio" name="'.$randomInt.'"/>
                         <label title="regular" for="'.$randomInt.'-star-2"></label>
                         <input id="'.$randomInt.'-star-1" type="radio" name="'.$randomInt.'"/>
                         <label title="poor" for="'.$randomInt.'-star-1"></label>
                         <input id="'.$randomInt.'-star-0" type="radio" name="'.$randomInt.'"/>
                         <label title="bad" for="'.$randomInt.'-star-0"></label>
                         </div>
                         </div>';
    }

    protected function getResultPopUp($baseurl, $id, $site){
        $carcass = '';
        $resultPopUp = $this->getDoctrine()
            ->getRepository('AppBundle:ResultsPopUp')
            ->createQueryBuilder('r')
            ->select('r,s')
            ->join('r.borderStyle', 's')
            ->where('r.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        if (!$resultPopUp) {
            throw $this->createNotFoundException(
                'No popUp found for id ' . $id
            );
        };

        if(isset($site) && !empty($site) && $site != null){
            $position = $this->getPosition($site);
        } else {
            $position = 'bottom: 20px; right: 20px;';
        }


                if(isset($resultPopUp[0]) && !empty($resultPopUp[0])){

                    $style = array(

                        'pop-up'            => "box-shadow: 0 0 10px rgba(0,0,0,0.5); font-family: \"Helvetica Neue\",Helvetica,Arial,sans-serif; z-index: 999; padding: ".$resultPopUp[0]['mainPopupPadding']."px; height: ".$resultPopUp[0]['height']."px;  position: fixed; width: ".$resultPopUp[0]['width']."px; background-color: ".$resultPopUp[0]['bgColor'].";
                                            color: ".$resultPopUp[0]['textColor']."; border-radius: ".$resultPopUp[0]['borderRadius']."px; border: ".$resultPopUp[0]['borderWidth']."px ".$resultPopUp[0]['borderStyle']['type']."
                                            ".$resultPopUp[0]['borderColour']."; ".$position,
                        'hr'                => "width: 90%; box-shadow: 0 0 10px rgba(0,0,0,0.5); margin-top: 20px; margin-bottom: 20px; border: 0; border-top: 1px solid #eee;",
                        'image-container'   => "width:100%;",
                        'answer-container'  => "width:100%; margin-top: 9%; height: 17%",
                        'header-image'      => "width: inherit; margin-top: ".$resultPopUp[0]['imageTopMargin']."px; margin-bottom: ".$resultPopUp[0]['imageMarginBottom']."px; max-width: ".$resultPopUp[0]['imageSizeHead']."px; max-height: ".$resultPopUp[0]['imageSizeHead']."px; display: block; background-size: contain; margin: 0 auto; padding: 5px;",
                        'body-image'        => "max-width: ".$resultPopUp[0]['imageSizeBody']."px; max-height: ".$resultPopUp[0]['imageSizeBody']."px; display: block; background-size: contain; margin: 0 auto; padding: 5px;",
                        'ratingTextOne'     => "font-size: ".$resultPopUp[0]['ratingTextSize']."pt;",
                        'ratingTextTwo'     => "font-size: ".$resultPopUp[0]['ratingTextSize']."pt;",
                        'main-question'     => "font-size: ".$resultPopUp[0]['mainTitleTextSize']."pt; text-align: center; line-height: 1; word-wrap: break-word;",
                        'go-to'             => "height: ".$resultPopUp[0]['buttonHeight']."px; width: ".$resultPopUp[0]['buttonWidth']."px; background-color: ".$resultPopUp[0]['buttonColor']."
                                                ;border: none; color: ".$resultPopUp[0]['buttonTextColour']."; margin: 0 auto; margin-top: ".$resultPopUp[0]['buttonTopMargin']."px; display: block; cursor: pointer;",
                        'rating-container'  => "padding-left: 20px; padding-right: 15px;",
                        'container-fluid'   => "margin-top: 10px;",
                        'text-fluid-str'    => "background-color: inherit; border: none; word-wrap: break-word; text-align: center; color: ".$resultPopUp[0]['textColor']."; font-family: Helvetica Neue,Helvetica,Arial,sans-serif;
                                                font-size: ".$resultPopUp[0]['textBlockMessageSize']."pt;",
                        'reviewStars-input' => "float: right; overflow: hidden; *zoom: 1;",
                        'reviewStars-input-two' => "float: right; overflow: hidden; *zoom: 1;",
                        'close-result-pop-up'   => "max-width: 18px; cursor: pointer; max-height: 18px; margin-right: 8px; margin-top: 8px; margin-bottom: -18px; position: absolute; right: 5px;",


                        'rating-star-styles'=> "
                                                .reviewStars-input-two input:checked ~ label, .reviewStars-input-two label,
                                                .reviewStars-input-two label:hover, .reviewStars-input-two label:hover ~ label {
                                                    background: url('".$baseurl."/app/system_image/star-image.png') no-repeat;
                                                };

                                                .reviewStars-input input:checked ~ label, .reviewStars-input label,
                                                .reviewStars-input label:hover, .reviewStars-input label:hover ~ label {
                                                    background: url('".$baseurl."/app/system_image/star-image.png') no-repeat;
                                                };
                                                .reviewStars-input input, .reviewStars-input-two input {
                                                    opacity: 0;
                                                    position: absolute;
                                                    z-index: 0;
                                                };
                                                .reviewStars-input input:checked ~ label, .reviewStars-input-two input:checked ~ label {
                                                    background-position: 0 -18px;
                                                    height: 18px;
                                                    width: 20px;
                                                };
                                                .reviewStars-input label, .reviewStars-input-two label {
                                                    background-position: 0 0;
                                                    height: 18px;
                                                    width: 20px;
                                                    float: right;
                                                    cursor: pointer;
                                                    margin-right: 10px;
                                                    position: relative;
                                                    z-index: 1;
                                                };
                                                .reviewStars-input label:hover, .reviewStars-input label:hover ~ label, .reviewStars-input-two label:hover, .reviewStars-input-two label:hover ~ label {
                                                    background-position: 0 -18px;
                                                    height: 18px;
                                                    width: 20px;
                                                };"
                    );

                    $carcass = "<style></style><div id='main-pop-up-container' itemscope='".$resultPopUp[0]['id']."' class='pop-up' style='".$style['pop-up']."'>
                    <div><img id='close-popup-window' class='close-result-pop-up' style='".$style['close-result-pop-up']."' src='".$baseurl."/app/system_image/close-button.png'></div>";
                        if (isset($resultPopUp[0]['imageFileHead']) && !empty($resultPopUp[0]['imageFileHead'])){
                            $carcass.= "<div class='image-container' style='  margin-top: ".$resultPopUp[0]['imageTopMargin']."px; margin-bottom: ".$resultPopUp[0]['imageMarginBottom'] ."px;".$style['image-container']."'><img class='header-image' style='".$style['header-image']."' src='".$baseurl."/uploads/images/".$resultPopUp[0]['imageFileHead']."'></div>";
                        };
                        $carcass.="<hr style='".$style['hr']."'><div class='main-question' style='".$style['main-question']."'>".$resultPopUp[0]['mainTitle']."</div>";
                        if (isset($resultPopUp[0]['imageFileBody']) && !empty($resultPopUp[0]['imageFileBody'])){
                            $carcass.= "<a href='".$resultPopUp[0]['imageLink']."' target=\"_blank\"><div class='image-container' style='".$style['image-container']."'><img class='body-image' style='".$style['body-image']."' src='".$baseurl."/uploads/images/".$resultPopUp[0]['imageFileBody']."'></div></a>";
                        };

                    if($resultPopUp[0]['blockStatus'] == 'RB') {

                        $ratingCount = $resultPopUp[0]['countRating'];
                        if(empty($ratingCount)) $ratingCount = 1;

                        $carcass .= "<div class='answer-container' style='" . $style['answer-container'] . "'>";
                        $i = 1;
                        while($i <= $ratingCount){
                            $ratingText = '';
                            $widthString = '';
                            $containerIndex = '';
                            $spanMargin = 4;
                            $marginBlock = 1;
                            switch ($i) {
                                case 1:
                                    $ratingText = $resultPopUp[0]['ratingTextOne'];
                                    $widthString = $resultPopUp[0]['indTextWidthOne'];
                                    $marginBlock = 2;
                                    $spanMargin = 6;
                                    break;
                                case 2:
                                    $ratingText = $resultPopUp[0]['ratingTextTwo'];
                                    $widthString = $resultPopUp[0]['indTextWidthTwo'];
                                    $containerIndex = $style['container-fluid'];
                                    $marginBlock = 2;
                                    $spanMargin = 6;
                                    break;
                                case 3:
                                    $ratingText = $resultPopUp[0]['ratingTextThree'];
                                    $widthString = $resultPopUp[0]['indTextWidthThree'];
                                    $containerIndex = $style['container-fluid'];
                                    $marginBlock = 2;
                                    $spanMargin = 2;
                                    break;
                            }
                            $ratingStyle = 'font-size: '.$resultPopUp[0]['ratingTextSize'].'pt; margin-top: '.$spanMargin.'px; display: inline-block; line-height: 1.1; word-wrap: break-word; width: '.$widthString.'px; ';
                            $carcass .= $this->getRating($ratingText, $ratingStyle, $this->generateRandomString(5), $containerIndex, $marginBlock);
                            $i++;
                        }
                       $carcass .= "</div>";
                    } else if ($resultPopUp[0]['blockStatus'] == 'TB'){
                        $carcass.='<div class="text-container-fluid" style=" margin: '.$resultPopUp[0]['textContainerFluidMargin'].'px 0 auto; font-size: '.$resultPopUp[0]['textBlockMessageSize'].'pt;
                        text-align: center; word-wrap: break-word;"><pre class="text-fluid-str" style="'.$style['text-fluid-str'].'">'.$resultPopUp[0]['textBlockMessage'].'</pre></div>';
                    }

                    $carcass.='<a href="'.$resultPopUp[0]['url'].'" class="button-0" target="_blank" style="
                                                    color: '.$resultPopUp[0]['buttonTextColour'].';
                                                    background-color: '.$resultPopUp[0]['buttonColor'].';
                                                    width: '.$resultPopUp[0]['buttonWidth'].'px;
                                                    box-shadow: 0 5px '.$resultPopUp[0]['buttonShadow'].';
                                                    height: '.$resultPopUp[0]['buttonHeight'].'px;
                                                    margin-top: '.$resultPopUp[0]['buttonTopMargin'].'px;
                                            "><span class="inner-span-button" style="width: 100%; font-size: '.$resultPopUp[0]['buttonTextSize'].'pt">'.$resultPopUp[0]['buttonText'].'</span></a>';
                    $carcass .= '</div>
                                    </div>
                                       </div>
                                           </div>';
            }

        return $carcass;
    }

    public function getPosition($site){
        switch ($site[0]['positionAbbreviation']) {
            case self::UPPER_LEFT:
                $position = 'top: 20px; left: 20px;';
                break;
            case self::UPPER_RIGHT:
                $position = 'top: 20px; right: 20px;';
                break;
            case self::LOWER_LEFT:
                $position = 'left: 20px; bottom: 20px;';
                break;
            case self::LOWER_RIGHT:
                $position = 'bottom: 20px; right: 20px;';
                break;
            case self::CENTER_SCREEN:
                $position = 'top: 26%; left: 40%;';
                break;
            case null:
                $position = 'top: 20px; left: 20px;';
                break;
            default:
                $position = 'bottom: 20px; right: 20px;';
                break;
        }

        return $position;
    }

    public function getUserPopUp($baseurl, $id, $site){
        $userPopUp = $this->getDoctrine()
            ->getRepository('AppBundle:UserPopUp')
            ->createQueryBuilder('u')
            ->select('u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        if (!$userPopUp) {
            throw $this->createNotFoundException(
                'No userPopUp found for id ' . $id
            );
        };

        if(isset($site) && !empty($site) && $site != null){
            $position = $this->getPosition($site);
        } else {
            $position = 'bottom: 20px; right: 20px;';
        }

        $carcass = '<div id="main-pop-up-container" style="'.$position.' width: auto; z-index: 999; height: auto; position: fixed;">'.$userPopUp[0]['htmlCode'].'</div>';
        return $carcass;
    }

    public function corePreviewAction(Request $request){
        $response = new Response();

        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
        if ($request->getMethod() == Request::METHOD_POST) {

            $content = json_decode($request->getContent(), true);
            $switch = $content['Data']['key'];
            $significance = $content['Data']['significance'];
            switch ($switch) {
                case 'RP':
                    $container['container'] = $this->getResultPopUp($baseurl, $significance, null);
                    $container['checker'] = 'RP';
                    break;
                case 'UP':
                    $container['container'] = $this->getUserPopUp($baseurl, $significance, null);
                    $container['checker'] = 'UP';
                    break;
                case 'L':
                    $link = $this->getLink($significance);
                    isset($link) && !empty($link) ? $container['container'] = $link[0]['url'] : $container['container'] = null;
                    $container['checker'] = 'L';
                    break;
                case 'QP':
                    $question = $this->getQuestion($significance);
                    $answers = $this->getAnswers($significance);
                    $container['container'] = $this->getPopUp($content['Data']['popupId'], $baseurl, null, $question, $answers);
                    $container['checker'] = 'QP';
                    break;
                default:
                    $container['container'] = [];
                    $container['checker'] = 'MISFIRE';
                    break;
            }
            $response->setContent(json_encode($container));
        }

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', "Content-Type, Origin, Accept, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        return $response;
    }

    public function coreAction(Request $request){
        $response = new Response();
        $container = array();
        $enabled = true;

        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        if ($request->getMethod() == Request::METHOD_POST) {

            $content = json_decode($request->getContent(), true);
            $fullAddress = $content['Data']['fullPath'];

            if (isset($content) && !empty($content)){

                if(isset($content['Data']['testMode']) && !empty($content['Data']['testMode'])){
                    if($content['Data']['testMode'] == true){
                        $enabled = true;
                    }
                }
                $urlContent = explode("//", $content['Data']['siteUrl']);

                $site = $this->getDoctrine()
                    ->getRepository('AppBundle:Site')
                    ->createQueryBuilder('s')
                    ->select('s.id, IDENTITY(s.popUp) AS popUpId, IDENTITY(s.question) AS questionId, s.enabled, s.isSleep, s.note, s.siteUrl, s.attachedElement, s.name, p.name as positionName, p.abbreviation as positionAbbreviation')
                    ->join('s.popUpPosition', 'p')
                    ->where('s.siteUrl LIKE :siteUrl')
                    ->andWhere('s.protocol = :protocol')
                    ->andWhere('s.enabled = :enabled')
                    ->setParameters(array('siteUrl' => '%'.$urlContent[1].'%', 'enabled' => true, 'protocol' => $urlContent[0].'//'))
                    ->getQuery()
                    ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

                $box = array();
                if(!empty($site)){
                    foreach ($site as $el){
                        $p = strpos($fullAddress, $el['siteUrl']);
                        if ($p === false){
                            continue;
                        } else {
                            $box [] = $el;
                        }
                    }
                    $site = $box;
                }

                if(isset($site) && !empty($site)){

                    foreach($site as $str){
                        if($str['attachedElement'] == null){
                            if($str['isSleep'] == false){
                                $site = array($str);
                                break;
                            }
                        }
                    }

                    if($site[0]['enabled'] == true){
                        $switch = $content['Data']['key'];
                        $significance = $content['Data']['significance'];
                        switch ($switch) {
                            case 'RP':
                                $container['container'] = $this->getResultPopUp($baseurl, $significance, $site);
                                $container['checker'] = 'RP';
                                break;
                            case 'UP':
                                $container['container'] = $this->getUserPopUp($baseurl, $significance, $site);
                                $container['checker'] = 'UP';
                                break;
                            case 'L':
                                $link = $this->getLink($significance);
                                isset($link) && !empty($link) ? $container['container'] = $link[0]['url'] : $container['container'] = null;
                                $container['checker'] = 'L';
                                break;
                            case 'QP':
                                $question = $this->getQuestion($significance);
                                $answers = $this->getAnswers($significance);
                                $container['container'] = $this->getPopUp($site[0]['popUpId'], $baseurl, $site, $question, $answers);
                                $container['checker'] = 'QP';
                                break;
                            default:
                                $container['container'] = [];
                                $container['checker'] = 'MISFIRE';
                                break;
                        }
                    } else {
                        $container['container'] = [];
                        $container['checker'] = 'ACCESS_CLOSE';
                    }
                }
                $response->setContent(json_encode($container));
            }
        }

        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', "Content-Type, Origin, Accept, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        return $response;
    }


}