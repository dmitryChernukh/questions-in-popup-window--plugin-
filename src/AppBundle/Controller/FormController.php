<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 20.01.16
 * Time: 15:15
 */

namespace AppBundle\Controller;
use AppBundle\Entity\AdditionalDisplayConditions;
use AppBundle\Entity\PopUp;
use AppBundle\Entity\Question;
use AppBundle\Entity\Answers;
use AppBundle\Entity\ResultsPopUp;
use AppBundle\Entity\Site;
use AppBundle\Entity\UserPopUp;
use JavierEguiluz\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FormController extends BaseAdminController {

    use UserTrait;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

    }
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public $path;

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/images/';
    }

    /**
     * @Route("/saveCurrentPopUpForm", name="saveCurrentPopUpForm")
     */
    public function saveCurrentPopUpFormAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        $data = $request->request->all();
        $entity = $em->getRepository('AppBundle:PopUp')->find($data['id']);
        if (!$entity) {
            throw $this->createNotFoundException('No product found for id '.$data['id']);
        }
        $borderStyle = $em->getRepository('AppBundle:BorderStyle')->find($data['borderStyle']);

        $entity->setName($data['name']);
        $entity->setHeight($data['height']);
        $entity->setBorderRadius($data['borderRadius']);
        $entity->setBorderColour($data['borderColour']);
        $entity->setBorderWidth($data['borderWidth']);
        $entity->setMainQuestionTextSize($data['mainQuestionTextSize']);
        $entity->setAnswersTextSize($data['answersTextSize']);
        $entity->setWidth($data['width']);
        $entity->setBgColor($data['bgColor']);
        $entity->setButtonColor($data['buttonColor']);
        $entity->setTextColor($data['textColor']);
        $entity->setButtonTextColor($data['buttonTextColor']);

        $entity->setButtonSpace($data['buttonSpace']);
        $entity->setButtonColorBorder($data['ButtonBorderColor']);
        $entity->setButtonBorderRadius($data['ButtonBorderRadius']);
        $entity->setButtonTextAlign($data['ButtonTextAlign']);

        $entity->setPictureWidth($data['pictureWidth']);
        $entity->setPictureBorderRadius($data['pictureBorderRadius']);
        $entity->setTextTopMargin($data['textTopMargin']);
        $entity->setLetterSpacing($data['letterSpacing']);
        $entity->setFontStyle($data['fontStyle']);
        $entity->setFontWeight($data['fontWeight']);
        $entity->setPictureOpacity($data['pictureOpacity']);
        $entity->setLineHeight($data['lineHeight']);
        $entity->setPictureTopMargin($data['pictureTopMargin']);
        $entity->setAnswersContainerMarginTop($data['answersContainerMarginTop']);

        $entity->setBorderStyle($borderStyle);

        $em->merge($entity);
        $em->flush();

        $qb = $this->getDoctrine()->getRepository('AppBundle:PopUp')->createQueryBuilder('p');
        $qb ->select('p', 'r')
            ->leftJoin('p.borderStyle', 'r')
            ->where('p.id = :id')
            ->setParameter('id', $data['id']);
        $popUp = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        if(!$popUp[0]['borderStyle']){
            $popUp[0]['borderStyle']['id'] = 3;
            $popUp[0]['borderStyle']['type'] = 'none';
        }
        $popUp[0]['previousPage'] = $data['previousPage'];

        if (!$popUp) {
            var_dump($popUp); die;
        }
        return $this->render('popup/index.html.twig', $popUp[0]);
    }

    /**
     * @Route("/saveResultForm", name="saveResultForm")
     */
    public function formResultAction(Request $request){
        ini_set('memory_limit', '100M');
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();

        $entity = $em->getRepository('AppBundle:ResultsPopUp')->find($data['id']);
        if (!$entity) {
            throw $this->createNotFoundException('No product found for id '.$data['id']);
        }

        $borderStyle = $em->getRepository('AppBundle:BorderStyle')->find($data['borderStyle']);

        $entity->setName($data['name']);
        $entity->setHeight($data['height']);
        $entity->setBorderRadius($data['borderRadius']);
        $entity->setBorderColour($data['borderColour']);
        $entity->setBorderWidth($data['borderWidth']);
        $entity->setWidth($data['width']);
        $entity->setBgColor($data['bgColor']);
        $entity->setButtonColor($data['buttonColor']);
        $entity->setTextColor($data['textColor']);
        $entity->setBorderStyle($borderStyle);
        $entity->setButtonTopMargin($data['buttonTopMargin']);
        $entity->setTextBlockMessageSize($data['textBlockMessageSize']);
        $entity->setButtonTextColour($data['buttonTextColour']);
        $entity->setTextBlockMessage($data['textBlockMessage']);
        $entity->setBlockStatus($data['blockStatus']);
        $entity->setCountRating($data['rating-count']);
        $entity->setRatingTextOne($data['ratingTextOne']);
        $entity->setRatingTextTwo($data['ratingTextTwo']);
        $entity->setRatingTextThree($data['ratingTextThree']);
        $entity->setIndTextWidthOne($data['ratingTextOneWidth']);
        $entity->setIndTextWidthTwo($data['ratingTextTwoWidth']);
        $entity->setIndTextWidthThree($data['ratingTextThreeWidth']);
        $entity->setButtonShadow($data['shadowColor']);

        $entity->setTextContainerFluidMargin($data['textContainerFluidMargin']);
        $entity->setMainPopupPadding($data['mainPopupPadding']);
        $entity->setButtonTextSize($data['buttonTextSize']);
        $entity->setImageLink($data['imageLink']);

        $entity->setImageMarginBottom($data['imageMarginBottom']);
        $entity->setImageTopMargin($data['imageTopMargin']);

        $entity->setImageSizeHead($data['imageSizeHead']);
        $entity->setImageSizeBody($data['imageSizeBody']);
        $entity->setUpdated(time());
        $entity->setMainTitle($data['mainTitle']);
        $entity->setMainTitleTextSize($data['mainQuestionTextSize']);
        $entity->setRatingTextSize($data['ratingTextSize']);
        $entity->setUrl($data['url']);
        $entity->setButtonText($data['buttonText']);
        $entity->setButtonWidth($data['buttonWidth']);
        $entity->setButtonHeight($data['buttonHeight']);
        $entity->setNote($data['note']);


        if(isset($data['delete-image-body']) && $data['delete-image-body'] == 'on'){
            if(!empty($data['previous-image-body'])){
                unlink($this->getUploadRootDir().$data['previous-image-body']);
            }
            $entity->setImageFileBody(null);
        }

        if(isset($data['delete-image-head']) && $data['delete-image-head'] == 'on'){
            if(!empty($data['previous-image-head'])){
                unlink($this->getUploadRootDir().$data['previous-image-head']);
            }
            $entity->setImageFileHead(null);
        }

        if(isset($_FILES['head-image'])){
            $file1 = $_FILES['head-image'];
            if($file1['error'] == 0){
                $name = $this->generateHash();
                $uploadFileHead = $this->getUploadRootDir() . basename($name);
                if (move_uploaded_file($file1['tmp_name'], $uploadFileHead)) {
                    $entity->setImageFileHead($name);
                    if(isset($data['previous-image-head'])){
                        if(!empty($data['previous-image-head'])){
                            unlink($this->getUploadRootDir().$data['previous-image-head']);
                        }
                    }
                } else {
                    echo "Not uploaded because of error #".$_FILES["file"]["error"];
                    exit;
                }
            }
        }

        if(isset($_FILES['body-image'])){
            $file2 = $_FILES['body-image'];
            if($file2['error'] == 0){
                $name2 = $this->generateHash();
                $uploadFileBody = $this->getUploadRootDir() . basename($name2);
                if (move_uploaded_file($file2['tmp_name'], $uploadFileBody)) {
                    $entity->setImageFileBody($name2);
                    if(isset($data['previous-image-body'])){
                        if(!empty($data['previous-image-body'])){
                            unlink($this->getUploadRootDir().$data['previous-image-body']);
                        }
                    }
                }
            }
        }

        $em->merge($entity);
        $em->flush();

        return $this->redirect($data['currentPage']);
    }


    public function sendToSleepAction(Request $request){
        $response = new Response();
        $id = $request->query->get('siteId');

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Site')->find($id);
        if ($entity) {
            $entity->setIsSleep(true);
        }
        $em->merge($entity);
        $em->flush();
        $response->setContent(json_encode(array('status' => 'Sleep')));

        return $response;
    }

    /**
     * @Route("/saveStep", name="saveStep")
     */
    public function setFormAction(Request $request){
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Answers')->find($data['id']);
        if (!$entity) {
            throw $this->createNotFoundException('No answer found for id '.$data['id']);
        }

        $entity->setAnswer($data['answer']);
        $entity->setIdentifire($data['identifire']);
        $entity->setNote($data['note']);

        switch ($data['identifire']) {
            case 'L':
                $entity->setIdentifireStepID($data['StepID-link']);
                break;
            case 'QP':
                $entity->setIdentifireStepID($data['StepID-qp']);
                break;
            case 'RP':
                $entity->setIdentifireStepID($data['StepID-rp']);
                break;
        }
        $em->merge($entity);
        $em->flush();

        return $this->redirect($data['previousPage']);
    }

    /**
     * @Route("/saveUserPopUp", name="saveUserPopUp")
     */
    public function saveUserPopUpAction(Request $request){

        $response = new JsonResponse();
        error_reporting(E_ALL);
        $cssFileRoot = $_SERVER['DOCUMENT_ROOT'].'/app/css/user-style/';
        if (!file_exists($cssFileRoot)) {
            mkdir($cssFileRoot, 0777);
        }
        function random_string($length) {
            $key = '';
            $keys = array_merge(range(0, 9), range('a', 'z'));
            for ($i = 0; $i < $length; $i++) {
                $key .= $keys[array_rand($keys)];
            }
            return $key;
        }

        $randomName = 'file_'.random_string(12).'.css';

        if(($file= fopen($cssFileRoot.$randomName, "w"))==false){
            echo 'Can not create the file.';
            return false;
        }

        $data = $request->request->all();

        $em = $this->getDoctrine()->getManager();

        $resultPopUp = $this->getDoctrine()
            ->getRepository('AppBundle:PopUp')
            ->createQueryBuilder('p')
            ->select('p')
            ->where('p.id = :id')
            ->setParameter('id', $data['popUpId'])
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        try {
            //and in this place I created user-popup
            // and have bound it with popup
            $popup = new UserPopUp();
            $popup->setHtmlCode($data['html-code']);
            $popup->setCssCode($data['css-code']);
            $popup->setСssFile($randomName);
            $popup->setPopupName(empty($data['individual-popup-name']) ? 'No name' : $data['individual-popup-name']);
            $popup->setAdditionalInfo($data['dop-info']);

            if(isset($resultPopUp[0]['id'])){
                $popup->setPopupId($resultPopUp[0]['id']);
            }
            if(isset($data['separateStatus'])){
                $popup->setAdditionalStatus($data['separateStatus']);
            }

            $em->persist($popup);
            $em->flush();
            $resID = $popup->getId();

            fwrite($file, $data['css-code']);
            fclose($file);

        } catch (\Exception $e) {
            return null;
        }
        return $response->setData(array('id' => $resID));
    }

    /**
     * @Route("/previewPopUp", name="previewPopUp")
     */
    public function previewPopUpAction(Request $request)
    {
        $id = $request->query->get('id');
        $before = $request->query->get('currentPage');

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
        $popUp[0]['previousPage'] = $before;

        if (!$popUp) {
            var_dump($popUp); die;
        }
        return $this->render('popup/index.html.twig', $popUp[0]);
    }


    /**
     * @Route("/testRender", name="testRender")
     */
    public function testRenderAction(){
        phpinfo();
    }

    /**
     * @Route("/savePopUp", name="savePopUp")
     */
    public function savePopUpAction(Request $request){
            $fileHashString = bin2hex(mcrypt_create_iv(12, MCRYPT_DEV_URANDOM));
            $em = $this->getDoctrine()->getManager();
            $data = $request->request->all();
            $entity = $em->getRepository('AppBundle:PopUp')->find($data['id']);
            if (!$entity) {
                throw $this->createNotFoundException('No popUp found for id '.$data['id']);
            }
            $borderStyle = $em->getRepository('AppBundle:BorderStyle')->find($data['borderStyle']);

            $entity->setName($data['name']);
            $entity->setHeight($data['height']);
            $entity->setBorderRadius($data['borderRadius']);
            $entity->setBorderColour($data['borderColour']);
            $entity->setBorderWidth($data['borderWidth']);
            $entity->setMainQuestionTextSize($data['mainQuestionTextSize']);
            $entity->setAnswersTextSize($data['answersTextSize']);
            $entity->setWidth($data['width']);
            $entity->setBgColor($data['bgColor']);
            $entity->setButtonColor($data['buttonColor']);
            $entity->setButtonTextColor($data['buttonTextColor']);
            $entity->setTextColor($data['textColor']);

            $entity->setPictureWidth($data['pictureWidth']);
            $entity->setPictureBorderRadius($data['pictureBorderRadius']);
            $entity->setTextTopMargin($data['textTopMargin']);
            $entity->setLetterSpacing($data['letterSpacing']);
            $entity->setFontStyle($data['fontStyle']);
            $entity->setFontWeight($data['fontWeight']);
            $entity->setPictureOpacity($data['pictureOpacity']);
            $entity->setLineHeight($data['lineHeight']);
            $entity->setPictureTopMargin($data['pictureTopMargin']);
            $entity->setAnswersContainerMarginTop($data['answersContainerMarginTop']);

            $entity->setBorderStyle($borderStyle);
            $entity->setUpdated(time());
            $entity->setNote($data['note']);
            $uploadFile = $this->getUploadRootDir() . basename($fileHashString);

            if(isset($data['delete-image']) && $data['delete-image'] == 'on'){
                unlink($this->getUploadRootDir().$data['previous-image']);
                $entity->setImage(null);
                $entity->setOriginalImageName(null);
                $entity->setImageHashName(null);
            }
            if (move_uploaded_file($_FILES['file-image']['tmp_name'], $uploadFile)) {
                $entity->setImage($fileHashString);
                $entity->setOriginalImageName($_FILES['file-image']['name']);
                $entity->setImageHashName($fileHashString);

                echo "File is valid, and was successfully uploaded.\n";
            }
            $em->merge($entity);
            $em->flush();

        return $this->redirect($data['current-page-popup']);
    }

    /**
     * @Route("/addQuestion", name="addQuestion")
     */
    public function addQuestionAction(Request $request){
        sleep(2);
        $response = new JsonResponse();
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        try {
            $question = new Question();
            $question->setQuestion($data['questionName']);
            $question->setPopUpID($data['popUpId']);
            $em->persist($question);
            $em->flush();
            $id = $question->getId();
        } catch (\Exception $e) {
            return null;
        }

        return $response->setData(array('questionId' => $id, 'popUpId' => $data['popUpId'], 'questionName' => $data['questionName']));
    }

    /**
     * @Route("/getQuestions", name="getQuestions")
     */
    public function getQuestionsAction(Request $request){

        $response = new JsonResponse();
        $data = $request->request->all();
        $id = $data['popUpId'];

        $qb = $this->getDoctrine()->getRepository('AppBundle:Question')->createQueryBuilder('q');
        $qb ->select('q')
            ->where('q.popUpID = :i')
            ->andWhere('q.id != :q')
            ->setParameter('i', $id)
            ->setParameter('q', $data['questionId']);
            $Questions = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            $resultPopUp = $this->getDoctrine()
                ->getRepository('AppBundle:ResultsPopUp')
                ->createQueryBuilder('r')
                ->select('r')
                ->where('r.popUpId = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            $usersPopUp = $this->getDoctrine()
                ->getRepository('AppBundle:UserPopUp')
                ->createQueryBuilder('u')
                ->select('u')
                ->where('u.popupId = :id')
                ->setParameter('id', $id)
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        return $response->setData(array('questions' => $Questions, 'resultPopUp' => $resultPopUp, 'userPopUp' => $usersPopUp));
    }

    /**
     * @Route("/addAnswer", name="addAnswer")
     */
    public function addAnswerAction(Request $request){
        $id = 0;
        $response = new JsonResponse();
        sleep(1);
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('AppBundle:Question')->find($data['question']);
        if(!empty($data)){
            $answer = new Answers();
            $answer->setAnswer($data['answer']);
            $answer->setIdentifire($data['identifire']);
            $answer->setIdentifireStepID($data['identifireStepID']);

            $answer->setQuestion($question);
            $em->persist($answer);
            $em->flush();
            $id = $answer->getId();
        }

        return $response->setData(array('answerId' => $id, 'answer' => $data['answer'], 'identifire' => $data['identifire'], 'identifireStepID' => $data['identifireStepID']));
    }

    /**
     * @Route("/updateAnswer", name="updateAnswer")
     */
    public function updateAnswerAction(Request $request){

        $response = new JsonResponse();
        sleep(1);
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        $entity = $em->getRepository('AppBundle:Answers')->find($data['answerId']);
        if (!$entity) {
            throw $this->createNotFoundException('No answer found for id '.$data['answerId']);
        }
        $entity->setAnswer($data['answer']);
        $entity->setIdentifire($data['identifire']);
        $entity->setIdentifireStepID($data['identifireStepID']);
        $em->merge($entity);
        $em->flush();

        return $response->setData(array('answerId' => $data['answerId'], 'answer' => $data['answer'], 'identifire' => $data['identifire'], 'identifireStepID' => $data['identifireStepID']));
    }

    /**
     * @Route("/setTheCheckbox", name="setTheCheckbox")
     */
    public function setTheCheckbox(Request $request){
        $response = new JsonResponse();
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();

        $site = $em->getRepository('AppBundle:Site')
            ->findBy(array('popUp' => $data['parentElement']));

        if(!empty($site)){
            $entity = $site[0];
            $entity->setEnabled($data['status']);

            $em->merge($entity);
            $em->flush();
        }

        return $response->setData(array('status'=>true));
    }

    /**
     * @Route("/deleteAnswer", name="deleteAnswer")
     */
    public function deleteAnswerAction(Request $request){

        $response = new JsonResponse();
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $answer = $em->getRepository('AppBundle:Answers')->find($data['answerId']);

        if (!$answer) {
            throw $this->createNotFoundException('No answer found');
        }
        $em->remove($answer);
        $em->flush();
        return $response->setData(array('answerId' => $data['answerId']));
    }

    protected function generateHash(){
        return  bin2hex(mcrypt_create_iv(12, MCRYPT_DEV_URANDOM));
    }

    /**
     * @Route("/developTools", name="developTools")
     */
    public function developToolsAction(){
        var_dump($_SERVER['HTTP_HOST'].'/app/system_image/close-button.png');
        print_r($this->generateRandomString(5));


        exit;
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

    /**
     * @Route("/saveResult", name="saveResult")
     */
    public function saveResultAction(Request $request){
        $response = new JsonResponse();



        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();

        $entity = new ResultsPopUp();
        $borderStyle = $em->getRepository('AppBundle:BorderStyle')->find($data['borderStyle']);

        $qb = $this->getDoctrine()->getRepository('AppBundle:BorderStyle')->createQueryBuilder('q');
        $qb ->select('q')
            ->where('q.id = :i')
            ->setParameter('i', $data['borderStyle']);
        $style = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $random = $this->generateRandomString(5);

            $entity->setName($data['name']);
            $entity->setHeight($data['height']);
            $entity->setBorderRadius($data['borderRadius']);
            $entity->setBorderColour($data['borderColour']);
            $entity->setBorderWidth($data['borderWidth']);
            $entity->setWidth($data['width']);
            $entity->setBgColor($data['bgColor']);
            $entity->setButtonColor($data['buttonColor']);
            $entity->setTextColor($data['textColor']);
            $entity->setBorderStyle($borderStyle);
            $entity->setImageSizeHead($data['imageSizeHead']);
            $entity->setImageSizeBody($data['imageSizeBody']);
            $entity->setUpdated(time());
            $entity->setMainTitle($data['mainTitle']);
            $entity->setMainTitleTextSize($data['mainQuestionTextSize']);

            $entity->setRatingTextOne($data['ratingTextOne']);
            $entity->setRatingTextTwo($data['ratingTextTwo']);
            $entity->setRatingTextSize($data['ratingTextSize']);

            $entity->setCountRating($data['rating-count']);
            $entity->setRatingTextThree($data['ratingTextThree']);

            $entity->setTextContainerFluidMargin($data['textContainerFluidMargin']);
            $entity->setMainPopupPadding($data['mainPopupPadding']);

            $entity->setUrl($data['url']);
            $entity->setButtonText($data['buttonText']);
            $entity->setButtonWidth($data['buttonWidth']);
            $entity->setButtonHeight($data['buttonHeight']);
            $entity->setNote($data['note']);
            $entity->setPopUpId($data['parent-popUpId']);

            if(isset($data['separateStatus']) && !empty($data['separateStatus'])){
                $entity->setSeparateStatus($data['separateStatus']);
            }

            $entity->setButtonTopMargin($data['buttonTopMargin']);
            $entity->setTextBlockMessageSize($data['textBlockMessageSize']);
            $entity->setButtonTextColour($data['buttonTextColour']);
            $entity->setTextBlockMessage($data['textBlockMessage']);
            $entity->setBlockStatus($data['blockStatus']);

            $name = '';
            if(isset($_FILES['head-image'])){
                $file1 = $_FILES['head-image'];
                if($file1['error'] == 0){
                    $name = $this->generateHash();
                    $uploadFileHead = $this->getUploadRootDir() . basename($name);
                    if (move_uploaded_file($file1['tmp_name'], $uploadFileHead)) {
                        $entity->setImageFileHead($name);
                    }
                }
            }

            $name2 = '';
            if(isset($_FILES['body-image'])){
                $file2 = $_FILES['body-image'];
                if($file2['error'] == 0){
                    $name2 = $this->generateHash();
                    $uploadFileBody = $this->getUploadRootDir() . basename($name2);
                    if (move_uploaded_file($file2['tmp_name'], $uploadFileBody)) {
                        $entity->setImageFileBody($name2);
                    }
                }
            }
            $em->persist($entity);
            $em->flush();
            $id = $entity->getId();

            $container = '<div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="'.$random.'-headingOne">
                                    <h4 class="panel-title">
                                        <a role="button" data-toggle="collapse" data-parent="#do-accordion" href="#'.$random.'-collapseOne" aria-expanded="true" aria-controls="'.$random.'-collapseOne">
                                            '.$data['name'].'
                                        </a>
                                        <input type="hidden" value="'.$id.'" class="res-popup-id">
                                        <span class="label label-primary some-action edit-result-pop-up" style="margin-left: 10px">Edit</span>
                                        <span class="label label-danger some-action remove-current-pop-up-result">Remove</span>
                                    </h4>
                                </div>
                                <div id="'.$random.'-collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="'.$random.'-headingOne">
                                    <div class="panel-body">';

            $container .= '<div class="pop-up" style=" padding: '.$data['mainPopupPadding'].'px; height: '.$data['height'].'; width: '.$data['width'].'; background-color: '.$data['bgColor'].';
                         color: '.$data['textColor'].';
                         border-radius: '.$data['borderRadius'].'px;
                         border: '.$data['borderWidth'].'px '.$style[0]['type'].' '.$data['borderColour'].';
                         margin-left: 40%;">
                         <div style="margin-left: 90%;"><img class="close-result-pop-up" src="/app/system_image/close-button.png"></div>';
                         if(isset($uploadFileHead) && !empty($uploadFileHead)){
                         $container .= '<div class="image-container" style="width:100%;"><img class="header-image" style="width: inherit; max-width: '.$data['imageSizeHead'].'; max-height: '.$data['imageSizeHead'].';" src="/uploads/images/'.$name.'"></div>';
                         };
                         $container .= '<hr style="width: 90%; box-shadow: 0 0 10px rgba(0,0,0,0.5);"><div class="main-question" style="font-size: '.$data['mainQuestionTextSize'].'pt;
                         text-align: center; line-height: 1;">'.$data['mainTitle'].'</div>';
                         if(isset($uploadFileBody) && !empty($uploadFileBody)){
                         $container .= '<div class="image-container"><img class="body-image" style="width: inherit; max-width: '.$data['imageSizeBody'].'; max-height: '.$data['imageSizeBody'].';" src="/uploads/images/'.$name2.'"></div>';
                         };

            if($data['blockStatus'] == 'RB'){
            $container .= '<div class="answer-container" style="width:100%; margin-top: 9%; height: 17%">';

                $ratingCount = $data['rating-count'];

                function getRating($ratingText, $style, $randomInt, $marginBlock){
                    return '<div class="rating-container"><span id="" style="'.$style.'">'.$ratingText.'</span>
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
                $i = 1;
                while($i <= $ratingCount){
                    $ratingText = '';
                    $spanMargin = 4;
                    $marginBlock = 1;
                    switch ($i) {
                        case 1:
                            $ratingText = $data['ratingTextOne'];
                            $marginBlock = 2;
                            $spanMargin = 6;
                            break;
                        case 2:
                            $ratingText = $data['ratingTextTwo'];
                            $marginBlock = 2;
                            $spanMargin = 6;
                            break;
                        case 3:
                            $ratingText = $data['ratingTextThree'];
                            $marginBlock = 0;
                            $spanMargin = 2;
                            break;
                    }
                    $style = 'font-size: '.$data['ratingTextSize'].'pt; margin-top: '.$spanMargin.'px; display: inline-block; line-height: 1.1; word-wrap: break-word;';
                    $container .= getRating($ratingText, $style, $this->generateRandomString(5), $marginBlock);
                    $i++;
                }

                $container .= '</div> ';

            } else if($data['blockStatus'] == 'TB'){
            $container.='<div class="text-container-fluid" style=" margin: '.$data['textContainerFluidMargin'].' 0 auto; font-size: '.$data['textBlockMessageSize'].'pt;
                        text-align: center; word-wrap: break-word;">'.$data['textBlockMessage'].'</div>';
            }

            $container.='<a href="'.$data['url'].'" class="button-0" style="
                                                    color: '.$data['buttonTextColour'].';
                                                    background-color: '.$data['buttonColor'].';
                                                    width: '.$data['buttonWidth'].'px;
                                                    height: '.$data['buttonHeight'].'px;
                                                    margin-top: '.$data['buttonTopMargin'].'px;
                                            "><span class="inner-span-button">'.$data['buttonText'].'</span></a>';
            $container .= '</div>
                            </div>
                             </div>
                              </div>';

        return $response->setData(array('popUpData' => $data, 'popUpContainer' => $container, 'ImageHead' => isset($name) ? $name : '', 'ImageBody' => isset($name2) ? $name2 : '', 'popUpId' => $id));
    }

    /**
     * @Route("/editResultPopUp", name="editResultPopUp")
     */
    public function editResultPopUpAction(Request $request){

        if($request->getMethod() == 'GET'){
            $index = $request->query->get('index');
            $before = $request->query->get('currentPage');
            if($index != null){
                $qb = $this->getDoctrine()->getRepository('AppBundle:ResultsPopUp')->createQueryBuilder('p');
                $qb ->select('p', 'r')
                    ->leftJoin('p.borderStyle', 'r')
                    ->where('p.id = :id')
                    ->setParameter('id', $index);
                $popUp = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

                if(!$popUp[0]['borderStyle']){
                    $popUp[0]['borderStyle']['id'] = 3;
                    $popUp[0]['borderStyle']['type'] = 'none';
                }
                $popUp[0]['previousPage'] = $before;
                if (!$popUp) {
                    throw $this->createNotFoundException(
                        'No popUp found for id '.$index
                    );
                }
                return $this->render('popup/resultsPopUp.html.twig', $popUp[0]);
            }
        }
    }

    /**
     * @Route("/cloneCurrentPopup", name="cloneCurrentPopup")
     */
    public function cloneCurrentPopupAction(Request $request){
        $response = new JsonResponse();
        $index = $request->query->get('dataID');
        $em = $this->getDoctrine()->getManager();
        $qb = $this->getDoctrine()->getRepository('AppBundle:PopUp')->createQueryBuilder('p');
        $qb ->select('p, IDENTITY(p.borderStyle) AS popUpStyleId')
            ->where('p.id = :id')
            ->setParameter('id', $index);
        $popUp = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        if(isset($popUp) && !empty($popUp)){
            $popUpData = $popUp[0][0];
            $style = $em->getRepository('AppBundle:BorderStyle')->find($popUp[0]['popUpStyleId']);
            $newPopUp = new PopUp();
            $newPopUp->setName('New clone popUp');
            $newPopUp->setHeight($popUpData['height']);
            $newPopUp->setButtonTextColor($popUpData['buttonTextColor']);

            $newPopUp->setUpdated(time());
            $newPopUp->setBorderRadius($popUpData['borderRadius']);
            $newPopUp->setBorderColour($popUpData['borderColour']);
            $newPopUp->setBorderWidth($popUpData['borderWidth']);
            $newPopUp->setMainQuestionTextSize($popUpData['mainQuestionTextSize']);
            $newPopUp->setAnswersTextSize($popUpData['answersTextSize']);
            $newPopUp->setWidth($popUpData['width']);
            $newPopUp->setBgColor($popUpData['bgColor']);
            $newPopUp->setButtonColor($popUpData['buttonColor']);
            $newPopUp->setTextColor($popUpData['textColor']);

            $nameImage = $this->generateHash();
            $uploadFileHeadPopUp = $this->getUploadRootDir() . basename($nameImage);
                if (copy($this->getUploadRootDir().$popUpData['image'], $uploadFileHeadPopUp)) {
                    $newPopUp->setImage($nameImage);
                    $newPopUp->setImageHashName($nameImage);
                }

            $newPopUp->setBorderStyle($style);
            $em->persist($newPopUp);
            $em->flush();

            $id = $newPopUp->getId();

            //clone result-popup
            $rp = $this->getDoctrine()->getRepository('AppBundle:ResultsPopUp')->createQueryBuilder('p');
            $rp ->select('p, IDENTITY(p.borderStyle) AS popUpStyleId')
                ->where('p.popUpId = :id')
                ->setParameter('id', $index);
            $resPopUps = $rp->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            if(isset($resPopUps) && !empty($resPopUps)){
                foreach($resPopUps as $resPopUp){

                    $styleRes = $em->getRepository('AppBundle:BorderStyle')->find($resPopUp['popUpStyleId']);
                    $newPopUpResult = new ResultsPopUp();

                    $newPopUpResult->setName('New clone ResultPopUp');
                    $newPopUpResult->setHeight($resPopUp[0]['height']);
                    $newPopUpResult->setBorderRadius($resPopUp[0]['borderRadius']);
                    $newPopUpResult->setBorderColour($resPopUp[0]['borderColour']);
                    $newPopUpResult->setBorderWidth($resPopUp[0]['borderWidth']);
                    $newPopUpResult->setWidth($resPopUp[0]['width']);
                    $newPopUpResult->setBgColor($resPopUp[0]['bgColor']);
                    $newPopUpResult->setButtonColor($resPopUp[0]['buttonColor']);
                    $newPopUpResult->setTextColor($resPopUp[0]['textColor']);
                    $newPopUpResult->setBorderStyle($styleRes);
                    $newPopUpResult->setButtonTopMargin($resPopUp[0]['buttonTopMargin']);
                    $newPopUpResult->setTextBlockMessageSize($resPopUp[0]['textBlockMessageSize']);
                    $newPopUpResult->setButtonTextColour($resPopUp[0]['buttonTextColour']);
                    $newPopUpResult->setTextBlockMessage($resPopUp[0]['textBlockMessage']);
                    $newPopUpResult->setBlockStatus($resPopUp[0]['blockStatus']);
                    $newPopUpResult->setImageSizeHead($resPopUp[0]['imageSizeHead']);
                    $newPopUpResult->setImageSizeBody($resPopUp[0]['imageSizeBody']);
                    $newPopUpResult->setUpdated(time());
                    $newPopUpResult->setPopUpId($id);
                    $newPopUpResult->setMainTitle($resPopUp[0]['mainTitle']);
                    $newPopUpResult->setMainTitleTextSize($resPopUp[0]['mainTitleTextSize']);
                    $newPopUpResult->setRatingTextOne($resPopUp[0]['ratingTextOne']);
                    $newPopUpResult->setRatingTextTwo($resPopUp[0]['ratingTextTwo']);
                    $newPopUpResult->setRatingTextSize($resPopUp[0]['ratingTextSize']);
                    $newPopUpResult->setUrl($resPopUp[0]['url']);
                    $newPopUpResult->setButtonText($resPopUp[0]['buttonText']);
                    $newPopUpResult->setButtonWidth($resPopUp[0]['buttonWidth']);
                    $newPopUpResult->setButtonHeight($resPopUp[0]['buttonHeight']);
                    $newPopUpResult->setNote($resPopUp[0]['note']);

                    $nameHead = $this->generateHash();
                    $nameBody = $this->generateHash();
                    $uploadFileHead = $this->getUploadRootDir() . basename($nameHead);
                    $uploadFileBody = $this->getUploadRootDir() . basename($nameBody);

                    copy($this->getUploadRootDir().$resPopUp[0]['imageFileHead'], $uploadFileHead);
                    $newPopUpResult->setImageFileHead($nameHead);

                    copy($this->getUploadRootDir().$resPopUp[0]['imageFileBody'], $uploadFileBody);
                    $newPopUpResult->setImageFileBody($nameBody);

                    $em->persist($newPopUpResult);
                    $em->flush();
                }
            }

            //clone userPopUp
            $up = $this->getDoctrine()->getRepository('AppBundle:UserPopUp')->createQueryBuilder('u');
            $up ->select('u')
                ->where('u.popupId = :id')
                ->setParameter('id', $index);
            $userPopUps = $up->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            if(isset($userPopUps) && !empty($userPopUps)){
                foreach($userPopUps as $userPopUp){
                    $userPUp = new UserPopUp();
                    $userPUp->setHtmlCode($userPopUp['htmlCode']);
                    $userPUp->setPopupId($id);
                    $userPUp->setСssFile($userPopUp['cssFile']);
                    $userPUp->setCssCode($userPopUp['cssCode']);
                    $userPUp->setPopupName('New user\'s popup. Clone.');
                    $userPUp->setAdditionalInfo($userPopUp['additionalInfo']);

                    $em->persist($userPUp);
                    $em->flush();
                }
            }
        }
        return $response->setData(array('status' => 'clone'));
    }

    /**
     * @Route("/deleteUserPopUp", name="deleteUserPopUp")
     */
    public function deleteUserPopUpAction(Request $request){
        $obj = $this;
        $response = new JsonResponse();
        $index = $request->query->get('dataID');
            $em = $this->getDoctrine()->getManager();
            $sites = $em->getRepository('AppBundle:Site')
                ->findByPopUp($index);
            foreach ($sites as $site) {
                $em->remove($site);
            }

        function removeResults($index, $obj, $em){
            $qb = $obj->getDoctrine()->getRepository('AppBundle:ResultsPopUp')->createQueryBuilder('r');
            $qb ->select('r')
                ->where('r.popUpId = :id')
                ->setParameter('id', $index);
            $resultPopUp = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            if(!empty($resultPopUp)){
                foreach($resultPopUp as $rRemove){
                    $results = $em->getRepository('AppBundle:ResultsPopUp')->find($rRemove['id']);
                    $em->remove($results);
                }
            }

            $tb = $obj->getDoctrine()->getRepository('AppBundle:UserPopUp')->createQueryBuilder('u');
            $tb ->select('u')
                ->where('u.popupId = :id')
                ->setParameter('id', $index);
            $userPopUp = $tb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            if(!empty($userPopUp)){
                foreach($userPopUp as $uRemove){
                    $userPop = $em->getRepository('AppBundle:UserPopUp')->find($uRemove['id']);
                    $em->remove($userPop);
                }
            }
        }
        removeResults($index,$obj,$em);

        function removeAnswers($index, $obj, $em)
        {
            $qb = $obj->getDoctrine()->getRepository('AppBundle:Question')->createQueryBuilder('q');
            $qb ->select('q')
                ->where('q.popUpID = :id')
                ->setParameter('id', $index);
            $questions = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            if(!empty($questions)){
                foreach($questions as $qRemove){
                        $answers = $em->getRepository('AppBundle:Answers')
                            ->findByQuestion($qRemove['id']);
                        $question = $em->getRepository('AppBundle:Question')->find($qRemove['id']);
                        foreach ($answers as $answer) {
                            $em->remove($answer);
                        }
                        $em->remove($question);
                }
            }
        }
        removeAnswers($index,$obj,$em);

        $popup = $em->getRepository('AppBundle:PopUp')->find($index);
        $em->remove($popup);
        $em->flush();

        return $response->setData(array('popInfo' => 'deleted'));
    }

    /**
     * @Route("/addNewSite", name="addNewSite")
     */
    public function addNewSiteAction(Request $request){
        $id =0;
        $response = new JsonResponse();
        $index = $request->query->get('index');
        $data = $request->request->all();
        $fullAddr = parse_url($data['site-protocol'].$data['url'])['host'];

        if(isset(parse_url($data['site-protocol'].$data['url'])['path'])){
            $fullAddr.= parse_url($data['site-protocol'].$data['url'])['path'];
        }

        $data['url'] = parse_url($data['site-protocol'].$data['url'])['host'];

        $em = $this->getDoctrine()->getManager();
        $popUp = $em->getRepository('AppBundle:PopUp')->find($index);
        $question = $em->getRepository('AppBundle:Question')->find($data['availableQuestions']);
        $position = $em->getRepository('AppBundle:PopUpPosition')->find(4);
        $entity = $em->getRepository('AppBundle:Site')->findOneBy(array('popUp' => $index)); // 'siteUrl' => $site

        $qb = $this->getDoctrine()->getRepository('AppBundle:PopUp')->createQueryBuilder('p');
        $qb ->select('p.id, p.name');
        $allPopUp = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        if ($entity == null)
        {
            $siteExist = $this->getDoctrine()
                ->getRepository('AppBundle:Site')
                ->createQueryBuilder('s')
                ->select('s.id, s.enabled, s.note, IDENTITY(s.popUp) AS popupId, s.isSleep, s.attachedElement, s.siteUrl, s.name')
                ->where('s.siteUrl = :siteUrl')
                ->andWhere('s.protocol = :protocol')
                ->setParameters(array('siteUrl' => $fullAddr, 'protocol' => $data['site-protocol']))
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
            $containerPopup = [];

            foreach($siteExist as $element){
                if($element['attachedElement'] == null){
                    foreach($allPopUp as $currentPopup){
                        if($element['popupId'] == $currentPopup['id']){
                            $element['popUpName'] = $currentPopup['name'];
                            $containerPopup [] = $element;
                        }
                    }
                }

            }

            if(isset($siteExist) && !empty($siteExist)){
                foreach($siteExist as $site_ez){
                    if($site_ez['attachedElement'] == null && $site_ez['isSleep'] == false){
                        return $response->setData(array('siteId' => 0, 'error' => 'Exist', 'sites' => $containerPopup));
                    }
                }
            }

            $siteData = new Site();
            $siteData->setName($data['name']);
            $siteData->setSiteUrl($fullAddr);
            $siteData->setPopUp($popUp);
            $siteData->setProtocol($data['site-protocol']);
            $siteData->setQuestion($question);
            $siteData->setPopUpPosition($position); // By default - lower right corner
            if(isset($data['enabled-status'])){
                $siteData->setEnabled(1);
            } else {
                $siteData->setEnabled(0);
            }
            $em->persist($siteData);
            $em->flush();
            $id = $siteData->getId();
        }
        else
        {
            $siteExist = $this->getDoctrine()
                ->getRepository('AppBundle:Site')
                ->createQueryBuilder('s')
                ->select('s.id, IDENTITY(s.popUp) AS popUpId, IDENTITY(s.question) AS questionId, s.isSleep, s.enabled, s.attachedElement, s.note, s.siteUrl, s.name')
                ->where('s.siteUrl = :siteUrl')
                ->andWhere('s.protocol = :protocol')
                ->setParameters(array('siteUrl' => $fullAddr, 'protocol' => $data['site-protocol']))
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
            $containerPopup = array();

            foreach($siteExist as $element){
                if($element['attachedElement'] == null){
                    if($element['popUpId'] != $index){
                        foreach($allPopUp as $currentPopup){
                            if($element['popUpId'] == $currentPopup['id']){
                                $element['popUpName'] = $currentPopup['name'];
                                $containerPopup [] = $element;
                            }
                        }
                    }
                }
            }

            if(isset($siteExist) && !empty($siteExist)){
                foreach($siteExist as $site_ex){
                    if($site_ex['attachedElement'] == null){
                        if($site_ex['popUpId'] != $index && $site_ex['isSleep'] == false){
                            return $response->setData(array('siteId' => 0, 'error' => 'Exist', 'sites' => $containerPopup));
                        }
                    }
                }
            }

            $entity->setName($data['name']);
            $entity->setSiteUrl($fullAddr);
            $entity->setIsSleep(false);
            $entity->setProtocol($data['site-protocol']);
            $entity->setQuestion($question);
            if(isset($data['enabled-status'])){
                $entity->setEnabled(1);
            } else {
                $entity->setEnabled(0);
            }
            $em->persist($entity);
            $em->flush();
            $id = $entity->getId();

        }
        return $response->setData(array('siteId' => $id, 'error' => 'No exist'));
    }

    /**
     * @Route("/refreshQuestions", name="refreshQuestions")
     */
    public function refreshQuestionsAction(Request $request){
        $response = new JsonResponse();
        sleep(1);
        $index = $request->query->get('index');

        $qb = $this->getDoctrine()->getRepository('AppBundle:Question')->createQueryBuilder('q');
        $qb ->select('q', 'a')
            ->leftJoin('q.answers', 'a')
            ->where('q.popUpID = :id')
            ->setParameter('id', $index);
        $Questions = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        return $response->setData(array('questions' => $Questions));
    }

    /**
     * @Route("/stl-edit-user", name="stl-edit-user")
     */
    public function editUserPopupAction(Request $request){
        $index = $request->query->get('id');
        $previousPage = $request->query->get('current');
        $qb = $this->getDoctrine()->getRepository('AppBundle:UserPopUp')->createQueryBuilder('u');
        $qb ->select('u')
            ->where('u.id = :id')
            ->setParameter('id', $index);
        $userPopUp = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $userPopUp[0]['previousPage'] = $previousPage;

        return $this->render('popup/usersPopUp.html.twig', $userPopUp[0]);
    }

    /**
     * @Route("/deleteCurrentElement", name="deleteCurrentElement")
     */
    public function deleteCurrentElementAction(Request $request){
        $response = new JsonResponse();
        $elementID = $request->query->get('dataID');

        $qb = $this->getDoctrine()->getRepository('AppBundle:UserPopUp')->createQueryBuilder('q');
        $qb ->select('q')
            ->where('q.id = :id')
            ->setParameter('id', $elementID);
        $result = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $cssFileRoot = $_SERVER['DOCUMENT_ROOT'].'/app/css/user-style/'.$result[0]['cssFile'];
        unlink($cssFileRoot);
        $em = $this->getDoctrine()->getManager();
        $popup = $em->getRepository('AppBundle:UserPopUp')->find($elementID);

        if (!$popup) {
            throw $this->createNotFoundException('No popUp found');
        }
        $em->remove($popup);
        $em->flush();
        return $response->setData(array('UserPopupId' => $elementID));
    }

    /**
     * @Route("/rebuildForm", name="rebuildForm")
     */
    public function rebuildFormAction(Request $request){
        sleep(2);
        $response = new JsonResponse();
        $data = $request->request->all();

        $em = $this->getDoctrine()->getManager();
        $request->query->get('data-stl');
        $entity = $em->getRepository('AppBundle:UserPopUp')->find($data['id']);
        if (!$entity) {
            throw $this->createNotFoundException('No UserPopUp found for id '.$data['id']);
        }

        $cssFileRoot = $_SERVER['DOCUMENT_ROOT'].'/app/css/user-style/';
        if (!file_exists($cssFileRoot)) {
            mkdir($cssFileRoot, 0777);
        }

        if(isset($data['file']) && !empty($data['file'])){

            if(($file= fopen($cssFileRoot.$data['file'], "w"))==false){
                echo 'Can not create the file.';
                return false;
            }

            fwrite($file, $data['cssCode']);
            fclose($file);

        } else {
            function random_string($length) {
                $key = '';
                $keys = array_merge(range(0, 9), range('a', 'z'));
                for ($i = 0; $i < $length; $i++) {
                    $key .= $keys[array_rand($keys)];
                }
                return $key;
            }
            $randomName = 'file_'.random_string(12).'.css';

            if(($file= fopen($cssFileRoot.$randomName, "w"))==false){
                echo 'Can not create the file.';
                return false;
            }

            fwrite($file, $data['cssCode']);
            fclose($file);

            $entity->setСssFile($randomName);
        }

        $entity->setCssCode($data['cssCode']);
        $entity->setHtmlCode($data['htmlCode']);
        $entity->setPopupName($data['popupName']);

        $em->merge($entity);
        $em->flush();
        return $response->setData(array('status' => true));
    }

    /**
     * @Route("/deleteCurrentPopUp", name="deleteCurrentPopUp")
     */
    public function deleteCurrentPopUpAction(Request $request){
        $response = new JsonResponse();
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $popup = $em->getRepository('AppBundle:ResultsPopUp')->find($data['id']);

        if (!$popup) {
            throw $this->createNotFoundException('No popUp found');
        }
        $em->remove($popup);
        $em->flush();
        return $response->setData(array('popupId' => $data['id']));
    }

    /**
     * @Route("/moveToArchive", name="moveToArchive")
     */
    public function moveToArchive(Request $request){
        $response = new JsonResponse();
        $elementID = $request->query->get('index');
        $em = $this->getDoctrine()->getManager();
        $popup = $em->getRepository('AppBundle:PopUp')->find($elementID);

        $popup->setArchive(true);
        $em->merge($popup);
        $em->flush();
        return $response->setData(array('popupId' => $elementID));
    }

    /**
     * @Route("/allPopUp", name="allPopUp")
    */
    public function allPopUp(Request $request){
        $response = new JsonResponse();
        $qb = $this->getDoctrine()->getRepository('AppBundle:PopUp')->createQueryBuilder('p');
        $qb ->select('p');
        return $response->setData(array('popUp' => $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY)));
    }

        /**
     * @Route("/deleteQuestion", name="deleteQuestion")
     */
    public function deleteQuestionAction(Request $request){
        $response = new JsonResponse();
        $data = $request->request->all();
        try {
            $em = $this->getDoctrine()->getManager();
            $answers = $em->getRepository('AppBundle:Answers')
                ->findByQuestion($data['questionId']);
            $question = $em->getRepository('AppBundle:Question')->find($data['questionId']);
            if (!$question) {
                throw $this->createNotFoundException('No question found');
            }
            foreach ($answers as $answer) {
                $em->remove($answer);
            }
            $em->remove($question);
            $em->flush();
        }
        catch (\Exception $e) {
            return $response->setData(array('haveError' => 'CannotRemove'));
        }
        return $response->setData(array('questionId' => $data['questionId'], 'haveError' => 'No'));
    }

    /**
     * @Route("/renameQuestion", name="renameQuestion")
     */
    public function renameQuestionAction(Request $request){
        $response = new JsonResponse();
        sleep(1);
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        $entity = $em->getRepository('AppBundle:Question')->find($data['questionId']);
        if (!$entity) {
            throw $this->createNotFoundException('No Question found for id '.$data['questionId']);
        }
        $entity->setQuestion($data['questionText']);
        $em->merge($entity);
        $em->flush();

        return $response->setData(array('question' => $data['questionId']));
    }

    /**
     * @Route("/extractFromArchive", name="extractFromArchive")
     */
    public function extractFromArchive(Request $request){
        $response = new JsonResponse();
        $elementID = $request->query->get('data_id');
        $em = $this->getDoctrine()->getManager();
        $popup = $em->getRepository('AppBundle:PopUp')->find($elementID);

        $popup->setArchive(false);
        $em->merge($popup);
        $em->flush();
        return $response->setData(array('popupId' => $elementID));
    }

    protected function receiveData($id){

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
        if(isset($popUp[0])){
            $popUp[0]['update'] = date('Y-m-d H:i:s', $popUp[0]['update']);
        }

        $qb = $this->getDoctrine()->getRepository('AppBundle:Question')->createQueryBuilder('q');
        $qb ->select('q', 'a')
            ->leftJoin('q.answers', 'a')
            ->where('q.popUpID = :id')
            ->setParameter('id', $id);
        $Questions = $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $answer = $this->getDoctrine()
            ->getRepository('AppBundle:Answers')
            ->createQueryBuilder('a')
            ->select('a.id, IDENTITY(a.question) AS question_id, a.answer, a.note, a.identifire, a.identifireStepID')
            ->where('a.question = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $question = $this->getDoctrine()
            ->getRepository('AppBundle:Question')
            ->createQueryBuilder('q')
            ->select('q')
            ->where('q.id != :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $questAnswer = $this->getDoctrine()
            ->getRepository('AppBundle:Question')
            ->createQueryBuilder('qs')
            ->select('qs')
            ->where('qs.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $links = $this->getDoctrine()
            ->getRepository('AppBundle:Links')
            ->createQueryBuilder('l')
            ->select('l')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $resultPopUp = $this->getDoctrine()
            ->getRepository('AppBundle:ResultsPopUp')
            ->createQueryBuilder('r')
            ->select('r')
            ->getQuery()
            ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        //$data = array ( 'answer' => $answer, 'questAnswer' => $questAnswer[0], 'question' => $question, 'links' => $links, 'resultPopUp' => $resultPopUp);
        $data = array ( 'popUp' => $popUp[0], 'questions' => $Questions );

        return $data;
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


    /**
     * @Route("/saveInRow", name="saveInRow")
     */
    public function saveInRowAction(Request $request){

        $response = new JsonResponse();
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:Site')->find($data['siteId']);
        $entity->setAppearance($data['condition']);
        $entity->setPopUpPosition($em->getRepository('AppBundle:PopUpPosition')->find($data['position']));

            if($data['value'] == 'null' OR is_null($data['value'])){
                $data['value'] = null;
            }
        $entity->setAppValue($data['value']);

        $em->merge($entity);
        $em->flush();

        return $response->setData(array('siteId' => $data['siteId']));
    }


    /**
     * @Route("/saveCondition", name="saveCondition")
     */
    public function saveConditionAction(Request $request){
        $response = new JsonResponse();
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();

        $index = $request->query->get('index');
        $positions = array();
        if(isset($data['action']) && $data['action'] == 'on'){


            $entity = $em->getRepository('AppBundle:Site')->find($index);
            if (!$entity) {
                return $response->setData(array('siteId' => $index, 'error' => 'NoSite'));
            }

            $additionalDisplayCond = array(
                'url' => (isset($data['certain-url']) && !empty($data['certain-url'])) ? $data['certain-url'] : null,
                'device-element' => (isset($data['device-element']) && !empty($data['device-element'])) ? $data['device-element'] : null,
                'show-single' => (isset($data['show-single']) && !empty($data['show-single'])) ? true : null
            );


            $additionalEntity = $em->getRepository('AppBundle:AdditionalDisplayConditions')->findOneBy(array('siteId' => $index));

            if(!$additionalEntity){

                $separateCondition = new AdditionalDisplayConditions();

                $separateCondition->setCertainDevice( isset($data['device-activity']) ? $data['device-element'] : null);
                $separateCondition->setSinglVisit( isset($data['show-single-activity']) ? 1 : null);
                $separateCondition->setSiteId( $index );
                $separateCondition->setUrl( isset($data['url-activity']) ? $data['certain-url'] : null );
                $em->persist($separateCondition);
                $em->flush();
            } else {
                $additionalEntity->setCertainDevice( isset($data['device-activity']) ? $data['device-element'] : null);
                $additionalEntity->setSinglVisit( isset($data['show-single-activity']) ? 1 : null);
                $additionalEntity->setSiteId( $index );
                $additionalEntity->setUrl( isset($data['url-activity']) ? $data['certain-url'] : null );

                $em->merge($additionalEntity);
                $em->flush();
            }

            if(isset($data['condition']) && !empty($data['condition'])){
                $entity->setAppearance($data['condition']);
                $entity->setPopUpPosition($em->getRepository('AppBundle:PopUpPosition')->find($data['popup-position']));
                if (array_key_exists($data['condition'], $data)) {
                    $entity->setAppValue($data[$data['condition']]);
                } else {
                    $entity->setAppValue(null);
                }
                $em->merge($entity);
                $em->flush();

                $site = $this->getDoctrine()
                    ->getRepository('AppBundle:Site')
                    ->createQueryBuilder('s')
                    ->select('s.id, IDENTITY(s.popUp) AS popUpId, IDENTITY(s.question) AS questionId, s.isSleep, s.subsite, s.appearance, s.attachedElement, s.attachedPopupId, s.appValue, s.enabled, s.note, s.siteUrl, s.name, p.name as positionName, p.abbreviation as positionAbbreviation')
                    ->join('s.popUpPosition', 'p')
                    ->where('s.id = :id')
                    ->setParameters(array('id'=>$index))
                    ->getQuery()
                    ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

                $positions = $links = $this->getDoctrine()
                    ->getRepository('AppBundle:PopUpPosition')
                    ->createQueryBuilder('p')
                    ->select('p')
                    ->getQuery()
                    ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

            }
        } else {
            return $response->setData(array('siteId' => $index, 'errorT' => 'NoWrite'));
        }
        return $response->setData(array('siteId' => $index, 'site' => $site[0], 'error' => 'No', 'position' => $positions, 'popups' => $this->getAllSeparatePopups()));
    }

}


