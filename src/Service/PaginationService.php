<?php
namespace App\Service;
use Exception;
use Twig\Environment;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginationService{
    /**
     * le nom de l'entité sur laquelle on va faire la pagination
     *
     * @var string
     */
    private $entity;
    /**
     * le nombre d'enregistrement à récupérer
     *
     * @var integer
     */
    private $limit = 10;
    /**
     * le numéro de la page courante
     *
     * @var integer
     */
    private $currentPage = 1;
    /**
     * le manager de doctrine qui permet entre autre de trouver le repository
     *
     * @var ObjectManager
     */ 
    private $manager;
    /**
     * le moteur de templating twig
     *
     * @var Twig\Environment
     */
    private $twig;
    /**
     * le nom de la route qu'on veut utiliser pour les boutons de navigation
     *
     * @var string
     */
    private $route;
    /**
     * le template qui continent la pagination
     *
     * @var string
     */
    private $template;
    /**
     * Constructeur du service de pagination qui sera appelé par Symfony
     * 
     * N'oubliez pas de configurer votre fichier services.yaml afin que Symfony sache quelle valeur
     * utiliser pour le $template
     *
     * @param ObjectManager $manager
     * @param Environment $twig
     * @param RequestStack $request
     * @param string $template
     */
    public function __construct(ObjectManager $manager, Environment $twig, RequestStack $request, $template){
        $this->manager = $manager;
        $this->twig    = $twig;
        $this->route   = $request->getCurrentRequest()->attributes->get('_route');
        $this->template = $template;
    }   

    /**
     * Permet d'afficher le rendu de la navigation au sein d'un template twig !
     * 
     * On se sert ici de notre moteur de rendu afin de compiler le template qui se trouve au chemin
     * de notre propriété $template, en lui passant les variables :
     * - page  => La page actuelle sur laquelle on se trouve
     * - pages => le nombre total de pages qui existent
     * - route => le nom de la route à utiliser pour les liens de navigation
     *
     * Attention : cette fonction ne retourne rien, elle affiche directement le rendu
     * 
     * @return void
     */
    public function display(){
        $this->twig->display($this->template, [
            'page'  => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }


    /**
     * Permet de récupérer les données paginées pour une entité spécifique
     * 
     * Elle se sert de Doctrine afin de récupérer le repository pour l'entité spécifiée
     * puis grâce au repository et à sa fonction findBy() on récupère les données dans une 
     * certaine limite et en partant d'un offset
     * 
     * @throws Exception si la propriété $entity n'est pas définie
     *
     * @return array
     */
    public function getData(){
        //tester si les donnees existes
        if(empty($this->entity)){
            throw new Exception("Vous n'avez pas spécifié l'entité sur laquelle il faut paginer, utilsier la méthode setEntity() ");

        }
        // 1) calculer l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;
        // 2) Demander au repository de trouver les éléments
        $repo  = $this->manager->getRepository($this->entity);
        // 3) Renvoyer les éléments 
        $data =  $repo->findBy([], [], $this->limit, $offset);
        return $data;
    }

    /**
     * Permet de récupérer le nombre de pages qui existent sur une entité particulière
     * 
     * Elle se sert de Doctrine pour récupérer le repository qui correspond à l'entité que l'on souhaite
     * paginer (voir la propriété $entity) puis elle trouve le nombre total d'enregistrements grâce
     * à la fonction findAll() du repository
     * 
     * @throws Exception si la propriété $entity n'est pas configurée
     * 
     * @return int
     */
    public function getPages(){
        if(empty($this->entity)){
            throw new Exception("Vous n'avez pas spécifié l'entité sur laquelle il faut paginer, utilsier la méthode setEntity() ");
        }
        $repo  = $this->manager->getRepository($this->entity);
        $total = count($repo->findAll());
        $pages = \ceil($total / $this->limit);
        return $pages;
    }
    public function getEntity()
    {
        return $this->entity;
    }
    public function setEntity($entity)
    {
        $this->entity = $entity;
        // ajouter un return pour pouvoir travailler avec les autres méthodes de cette entité
        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }
    public function setLimit($limit)
    {
        $this->limit = $limit;
        // ajouter un return pour pouvoir travailler avec les autres méthodes de cette entité
        return $this;
    }

    public function getCurrentPage()
    {
        return $this->currentPage;
    }
    public function setCurrentPage($page)
    {
        $this->currentPage = $page;
        // ajouter un return pour pouvoir travailler avec les autres méthodes de cette entité
        return $this;
    }

    public function getRoute()
    {
        return $this->route;
    }
    public function setRoute($route)
    {
        $this->route = $route;
        // ajouter un return pour pouvoir travailler avec les autres méthodes de cette entité
        return $this;
    }

    public function getTemplate()
    {
        return $this->template;
    }
    public function setTemplate($template)
    {
        $this->template = $template;
        // ajouter un return pour pouvoir travailler avec les autres méthodes de cette entité
        return $this;
    }
}