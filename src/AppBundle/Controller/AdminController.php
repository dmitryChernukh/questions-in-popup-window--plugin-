<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 20.01.16
 * Time: 14:15
 */
namespace AppBundle\Controller;
use AppBundle\Entity\ResultsPopUp;
use AppBundle\Entity\Site;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\JsonResponse;

class AdminController extends BaseAdminController
    {

   public function previewAction()
        {
            $id = $this->request->query->get('id');

                $qb = $this->getDoctrine()->getRepository('AppBundle:PopUp')->createQueryBuilder('p');
                $qb ->select('p', 'r')
                    ->leftJoin('p.borderStyle', 'r')
                    ->where('p.id = :id')
                    ->setParameter('id', $id);
            $popUp = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            if(!$popUp[0]['borderStyle']){
                $popUp[0]['borderStyle']['id'] = 3;
                $popUp[0]['borderStyle']['type'] = 'none';
            }


            if (!$popUp) {
                var_dump($popUp); die;
            }
            return $this->render('popup/index.html.twig', $popUp[0]);
        }

        public function previewResultAction(){
            $id = $this->request->query->get('id');

            $qb = $this->getDoctrine()->getRepository('AppBundle:ResultsPopUp')->createQueryBuilder('p');
            $qb ->select('p', 'r')
                ->leftJoin('p.borderStyle', 'r')
                ->where('p.id = :id')
                ->setParameter('id', $id);
            $popUp = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            if(!$popUp[0]['borderStyle']){
                $popUp[0]['borderStyle']['id'] = 3;
                $popUp[0]['borderStyle']['type'] = 'none';
            }

            if (!$popUp) {
                throw $this->createNotFoundException(
                    'No popUp found for id '.$id
                );
            }

            return $this->render('popup/resultsPopUp.html.twig', $popUp[0]);
        }

        public function goToArchiveAction(){
            $qb = $this->getDoctrine()->getRepository('AppBundle:PopUp')->createQueryBuilder('p');
            $qb ->select('p');


//            var_dump($qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY));

            return $this->render('action/archive.html.twig', array('popup' => $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY)));
        }

        public function mapAction(){
            $id = $this->request->query->get('id');
            $Guest = $this->getDoctrine()
                ->getRepository('AppBundle:Guest')
                ->createQueryBuilder('g')
                ->select('g')
                ->where('g.id = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            return $this->render('map/map.html.twig', $Guest[0]);
        }

        public function AddAnswerAction(){

            $questionId = $this->request->query->get('id');
            $data = $this->dataAction($questionId);

            return $this->render('action/answers.html.twig', $data);

        }

        public function showResultPopupAction(){

            $resultPopUp = $this->getAllSeparatePopups();
            $data['popups'] = $resultPopUp;
            $sites = $this->getAllSites();

            $data['userPopUp'] = [];
            $data['sites'] = $sites;
            $data['conditions'] = array(
                'SI' => 'Show immediately on page entry',
                'SA' => 'Show after a set number of seconds',
                'SV' => 'Show if the user visits a set number of pages',
                'SC' => 'Show only on certain URLS',
                'SE' => 'Show if the user is about to exit the site',
                'SD' => 'Show only if the user is on a certain device',
                'SO' => 'Show only once on a single page (not showing on repeat visits)'
            );
            $devices = array(
                0 => 'Mobile',
                1 => 'Tablet',
                2 => 'Mobile & Tablet',
                3 => 'Desktop',
                4 => 'iOS',
                5 => 'iPad',
                6 => 'iPhone',
                7 => 'iPod',
                8 => 'Android',
                9 => 'Android Phone',
                10 => 'Android Tablet',
                11 => 'BlackBerry',
                12 => 'Windows'
            );

            $usersPopUp = $this->getDoctrine()
                ->getRepository('AppBundle:UserPopUp')
                ->createQueryBuilder('r')
                ->select('r')
                ->where('r.additionalStatus = :status')
                ->setParameter('status', 'SU')
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            $positions = $links = $this->getDoctrine()
                ->getRepository('AppBundle:PopUpPosition')
                ->createQueryBuilder('p')
                ->select('p')
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
//            var_dump($data);
            $data['devices'] = $devices;
            $data['userPopUp'] = $usersPopUp;
            $data['positions'] = $positions;
            return $this->render('action/separatePopup.html.twig', $data);
        }

        public function getListOfSitesAction(){
            $response = new JsonResponse();
            return $response->setData(array('list' => $this->getAllSites()));
        }

        public function getAllSeparatePopups(){
            return $this->getDoctrine()
                ->getRepository('AppBundle:ResultsPopUp')
                ->createQueryBuilder('r')
                ->select('r,s')
                ->join('r.borderStyle', 's')
                ->where('r.separateStatus = :status')
                ->setParameter('status', 'separate')
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }

        public function getAllSites(){
           return $this->getDoctrine()
                ->getRepository('AppBundle:Site')
                ->createQueryBuilder('s')
                ->select('  s.id,
                            s.enabled,
                            s.appearance,
                            s.appValue,
                            s.subsite,
                            s.note,
                            s.protocol,
                            s.siteUrl,
                            s.attachedPopupId,
                            s.attachedElement,
                            s.name,
                            p.name as positionName,
                            p.abbreviation as positionAbbreviation')

                ->join('s.popUpPosition', 'p')
                ->where('s.attachedElement IN (:ids)')
                ->setParameter('ids',array('RP','UP'))
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }

        public function getAllUsersPopup(){
            return $this->getDoctrine()
                ->getRepository('AppBundle:UserPopUp')
                ->createQueryBuilder('r')
                ->select('r')
                ->where('r.additionalStatus = :status')
                ->setParameter('status', 'SU')
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }

        public function cloneAsResultAction(Request $request){
            $response = new JsonResponse();
            $em = $this->getDoctrine()->getManager();
            $index = $request->query->get('index');

            $qb = $this->getDoctrine()->getRepository('AppBundle:PopUp')->createQueryBuilder('p');
            $qb ->select('p', 'r')
                ->leftJoin('p.borderStyle', 'r')
                ->where('p.id = :id')
                ->setParameter('id', $index);
            $popUp = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            if(isset($popUp) && !empty($popUp)){
                $dataContainer = $popUp[0];

                $entity = new ResultsPopUp();

                if($dataContainer['borderStyle'] && !empty($dataContainer['borderStyle'])){
                    $borderStyle = $em->getRepository('AppBundle:BorderStyle')->find($dataContainer['borderStyle']['id']);
                } else {
                    $borderStyle = $em->getRepository('AppBundle:BorderStyle')->find(10);
                }

                $entity->setName('New result popup');
                $entity->setHeight($dataContainer['height']);
                $entity->setBorderRadius($dataContainer['borderRadius']);
                $entity->setBorderColour($dataContainer['borderColour']);
                $entity->setBorderWidth($dataContainer['borderWidth']);
                $entity->setWidth($dataContainer['width']);
                $entity->setBgColor($dataContainer['bgColor']);
                $entity->setButtonColor($dataContainer['buttonColor']);
                $entity->setTextColor($dataContainer['textColor']);
                $entity->setBorderStyle($borderStyle);
                $entity->setImageSizeHead($dataContainer['pictureWidth']);
                $entity->setImageSizeBody($dataContainer['pictureWidth']);
                $entity->setUpdated(time());
                $entity->setMainTitle("Example text");
                $entity->setMainTitleTextSize($dataContainer['mainQuestionTextSize']);

                $entity->setRatingTextOne('Rating 1');
                $entity->setRatingTextTwo('Rating 2');
                $entity->setRatingTextSize($dataContainer['answersTextSize']);
                $entity->setRatingTextThree('Rating 3');

                $entity->setTextContainerFluidMargin(10);
                $entity->setMainPopupPadding(5);
                $entity->setCountRating(2);
                $entity->setUrl('http://www.testsite.com');
                $entity->setButtonText('Go');
                $entity->setButtonWidth(150);
                $entity->setButtonHeight(40);
                $entity->setNote('Some text');
                $entity->setPopUpId($index);


                $entity->setButtonTopMargin(10);
                $entity->setTextBlockMessageSize(15);
                $entity->setButtonTextColour($dataContainer['buttonTextColor']);
                $entity->setTextBlockMessage('This is a text message');
                $entity->setBlockStatus('RB');


                $em->persist($entity);
                $em->flush();
                $id = $entity->getId();

                return $response->setData(array('popUpId' => $id, 'status' => 'Success'));

            }
        }

        public function addSiteToPopupAction(Request $request){
            $response = new JsonResponse();
            $data = $request->request->all();
            $em = $this->getDoctrine()->getManager();

            $data['url'] = parse_url($data['site-protocol'].$data['url'])['host'];

                    $question = '';
                    $a = true;
                    $counter = 1;

                    while($a == true){
                        $question = $em->getRepository('AppBundle:Question')->find($counter);
                        if($question != null){
                            break;
                        }
                        $counter++;
                    }

                    $siteData = new Site();

                    $siteData->setSiteUrl($data['url']);
                    $siteData->setName('Result popup');
                    $siteData->setProtocol($data['site-protocol']);
                    $siteData->setSubsite($data['internal-link']);


                    $siteData->setAttachedElement($data['attachedElement']);

                    if($data['attachedElement'] == 'UP'){
                        $siteData->setAttachedPopupId($data['user-popup-id']);
                    } else if($data['attachedElement'] == 'RP'){
                        $siteData->setAttachedPopupId($data['popup-id']);
                    }

                    $siteData->setQuestion($question);
                    $position = $em->getRepository('AppBundle:PopUpPosition')->find(4);

                    $siteData->setPopUpPosition($position); // By default - lower right corner
                    if(isset($data['enabled-status'])){
                        $siteData->setEnabled(1);
                    } else {
                        $siteData->setEnabled(0);
                    }
                    $em->persist($siteData);
                    $em->flush();
                    $id = $siteData->getId();
                    $error = "No exist";


            return $response->setData(array('siteId' => $id, 'elementStatus' => $data['attachedElement'], 'error' => $error, 'body' => $data, 'usersPopup' => $this->getAllUsersPopup(), 'popups' => $this->getAllSeparatePopups()));
        }

        public function checkTheSite($siteUrl, $attachedElement = 'RP', $protocol){
           return $this->getDoctrine()
                ->getRepository('AppBundle:Site')
                ->createQueryBuilder('s')
                ->select('s')
                ->where('s.siteUrl = :siteUrl')
                ->andWhere('s.protocol = :protocol')
                ->andWhere('s.attachedElement = :attachedElement')
                ->setParameters(array('siteUrl' => $siteUrl, 'attachedElement' => $attachedElement, 'protocol' => $protocol))
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        }

        public function removeCurrentSiteAction(Request $request){
            $response = new JsonResponse();
            $data = $request->request->all();

            if(isset($data) && !empty($data)){
                $em = $this->getDoctrine()->getManager();
                $site = $em->getRepository('AppBundle:Site')->find($data['dateId']);

                    if (!$site) {
                        echo "Not found";
                    }
                $em->remove($site);
                $em->flush();
            }
            return $response->setData(array('error' => 'No exist', 'body' => $data));
        }

        public function saveSiteChangesAction(Request $request){
            $response = new JsonResponse();
            $content = json_decode($request->getContent(), true);

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Site')->find($content['site-id']);

            $siteExist = $this->checkTheSite($content['site-url'], 'RP', $content['protocol']);
            $userSiteExist = $this->checkTheSite($content['site-url'], 'UP', $content['protocol']);
            $siteExist = array_merge($siteExist,$userSiteExist);
            $error = "Exist";
            if(!empty($siteExist) ){

                        $entity->setSiteUrl($content['site-url']);
                        $entity->setProtocol($content['protocol']);
                        $entity->setSubsite($content['site-page']);


                        if($content['radioStatus'] == 'UP'){
                            $entity->setAttachedPopupId($content['user-popup-id']);
                            $entity->setAttachedElement('UP');
                        } else if($content['radioStatus'] == 'RP'){
                            $entity->setAttachedPopupId($content['popup-id']);
                            $entity->setAttachedElement('RP');
                        }

                        if (isset($content['enabled-status']) && $content['enabled-status'] == true) {
                            $entity->setEnabled(1);
                        } else {
                            $entity->setEnabled(0);
                        }
                        $em->persist($entity);
                        $em->flush();
                        $error = "No exist";

            } else {
                $entity->setSiteUrl($content['site-url']);
                $entity->setProtocol($content['protocol']);

                if($content['radioStatus'] == 'UP'){
                    $entity->setAttachedPopupId($content['user-popup-id']);
                    $entity->setAttachedElement('UP');
                } else if($content['radioStatus'] == 'RP'){
                    $entity->setAttachedPopupId($content['popup-id']);
                    $entity->setAttachedElement('RP');
                }

                if (isset($content['enabled-status']) && $content['enabled-status'] == true) {
                    $entity->setEnabled(1);
                } else {
                    $entity->setEnabled(0);
                }
                $em->persist($entity);
                $em->flush();
                $error = "No exist";
            }

            return $response->setData(array('error' => $error, 'body' => $content));
        }


        public function configureAction(){
            $data['count'] = 0;
            $popUpId = $this->request->query->get('id');
            $data = $this->dataAction($popUpId);

            $data['active'] = 'edit-popup';
            if(isset($data['questions'])){
                $data['count'] = count($data['questions']);
            }

            return $this->render('action/answers.html.twig', $data);

        }


        protected function dataAction($id){

            $qb = $this->getDoctrine()->getRepository('AppBundle:PopUp')->createQueryBuilder('p');
            $qb ->select('p', 'r')
                ->leftJoin('p.borderStyle', 'r')
                ->where('p.id = :id')
                ->setParameter('id', $id);
            $popUp = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            if(!$popUp[0]['borderStyle']){
                $popUp[0]['borderStyle']['id'] = 3;
                $popUp[0]['borderStyle']['type'] = 'none';
            }

            if (!$popUp) {
                var_dump($popUp); die;
            }

            $conditions = array(
                'SI' => 'Show immediately on page entry',
                'SA' => 'Show after a set number of seconds',
                'SV' => 'Show if the user visits a set number of pages',
                'SC' => 'Show only on certain URLS',
                'SE' => 'Show if the user is about to exit the site',
                'SD' => 'Show only if the user is on a certain device',
                'SO' => 'Show only once on a single page (not showing on repeat visits)'
            );

            $devices = array(
                0 => 'Mobile',
                1 => 'Tablet',
                2 => 'Mobile & Tablet',
                3 => 'Desktop',
                4 => 'iOS',
                5 => 'iPad',
                6 => 'iPhone',
                7 => 'iPod',
                8 => 'Android',
                9 => 'Android Phone',
                10 => 'Android Tablet',
                11 => 'BlackBerry',
                12 => 'Windows'
            );

            if(isset($popUp[0])){
                $popUp[0]['update'] = date('Y-m-d H:i:s', $popUp[0]['update']);
            }

            $qb = $this->getDoctrine()->getRepository('AppBundle:Question')->createQueryBuilder('q');
            $qb ->select('q', 'a')
                ->leftJoin('q.answers', 'a')
                ->where('q.popUpID = :id')
                ->setParameter('id', $id);
            $Questions = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            $resultPopUp = $this->getDoctrine()
                ->getRepository('AppBundle:ResultsPopUp')
                ->createQueryBuilder('r')
                ->select('r', 'p')
                ->leftJoin('r.borderStyle', 'p')
                ->where('r.popUpId = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            $usersPopUp = $this->getDoctrine()
                ->getRepository('AppBundle:UserPopUp')
                ->createQueryBuilder('r')
                ->select('r')
                ->where('r.popupId = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            $site = $this->getDoctrine()
                ->getRepository('AppBundle:Site')
                ->createQueryBuilder('s')
                ->select('s.id, IDENTITY(s.popUpPosition) AS popUpPositionId, IDENTITY(s.question) AS questionId, s.enabled, s.appearance, s.appValue, s.protocol, s.note, s.siteUrl, s.name, q.id as quesId, q.question')
                ->leftJoin('s.question', 'q')
                ->where('s.popUp = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

                if(isset($site) && !empty($site)){
                    $siteAdditionalValue = $this->getDoctrine()
                        ->getRepository('AppBundle:AdditionalDisplayConditions')
                        ->createQueryBuilder('a')
                        ->select('a')
                        ->where('a.siteId = :id')
                        ->setParameter('id', $site[0]['id'])
                        ->getQuery()
                        ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
                } else {
                    $siteAdditionalValue = array();
                }

           $positions = $links = $this->getDoctrine()
                ->getRepository('AppBundle:PopUpPosition')
                ->createQueryBuilder('p')
                ->select('p')
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            if(count($siteAdditionalValue) > 0){
                if (isset($siteAdditionalValue[0])){
                    $addValue = $siteAdditionalValue[0];
                }
            } else {
                $addValue = [];
            };

            $data = array ( 'popUp' => $popUp[0],
                            'questions' => $Questions,
                            'positions' => $positions,
                            'resultPopUp' => $resultPopUp,
                            'site' => isset($site[0]) ? $site[0] : $site,
                            'conditions' => $conditions,
                            'devices' => $devices,
                            'userPopUp' => $usersPopUp,
                            'siteAdditionalValue' => $addValue);

            return $data;
        }

        public function appearanceAction(){

            $siteId = $this->request->query->get('id');

            $conditions = array(
                'SI' => 'Show immediately on page entry',
                'SA' => 'Show after a set number of seconds',
                'SV' => 'Show if the user visits a set number of pages',
                'SC' => 'Show only on certain URLS',
                'SE' => 'Show if the user is about to exit the site',
                'SD' => 'Show only if the user is on a certain device',
                'SO' => 'Show only once on a single page (not showing on repeat visits)'
            );

            $devices = array(
                0 => 'Mobile',
                1 => 'Tablet',
                2 => 'Mobile & Tablet',
                3 => 'Desktop',
                4 => 'iOS',
                5 => 'iPad',
                6 => 'iPhone',
                7 => 'iPod',
                8 => 'Android',
                9 => 'Android Phone',
                10 => 'Android Tablet',
                11 => 'BlackBerry',
                12 => 'Windows'
            );

            $site = $this->getDoctrine()
                ->getRepository('AppBundle:Site')
                ->createQueryBuilder('s')
                ->select('s.id, IDENTITY(s.popUp) AS popUpId, IDENTITY(s.question) AS questionId, s.enabled, s.appearance, s.appValue, s.note, s.siteUrl, s.name, p.name as positionName, p.abbreviation as positionAbbreviation')
                ->join('s.popUpPosition', 'p')
                ->where('s.id = :id')
                ->setParameter('id', $siteId)
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            $data = array('site' => $site[0], 'conditions' => $conditions, 'devices' => $devices);

            return $this->render('appearance/index.html.twig', $data);
        }
     }
