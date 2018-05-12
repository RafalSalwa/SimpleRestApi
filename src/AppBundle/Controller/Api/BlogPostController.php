<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\BlogPost;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class BlogPostController.
 */
class BlogPostController extends FOSRestController
{

    /**
     * Returns posts list.
     * @ApiDoc(
     *     section="Simple Blog",
     *     statusCodes={
     *         200="Returned when successful",
     *         400="Returned when there is no id in request",
     *         404={
     *           "Returned when post was not found",
     *         }
     *     }
     * )
     *
     * @Route(name="api.blog_post.list", path="/blog/posts")
     * @Method("GET")
     * @return \FOS\RestBundle\View\View
     */
    public function getPostsListAction()
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:BlogPost');
        $view = View::create();

        $posts = $repo->findAll();
        $view->setData($posts)->setStatusCode(RESPONSE::HTTP_OK);

        return $view;
    }

    /**
     * Returns single post data from given ID.
     * @ApiDoc(
     *     section="Simple Blog",
     *     statusCodes={
     *         200="Returned when successful",
     *         400="Returned when there is no id in request",
     *         404={
     *           "Returned when post was not found",
     *         }
     *     }
     * )
     *
     * @Route(name="api.blog_post.single", path="/blog/posts/{id}")
     * @Method("GET")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function getSinglePostAction($id)
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:BlogPost');
        $view = View::create();

        $post = $repo->find($id);
        if($post){
            $view->setData($post)->setStatusCode(RESPONSE::HTTP_OK);

        } else {
            $view->setData('No data found for current ID')->setStatusCode(RESPONSE::HTTP_NOT_FOUND);
        }

        return $view;
    }

    /**
     * Creates single post from data
     * @ApiDoc(
     *     section="Simple Blog",
     *     statusCodes={
     *         201="Returned when successful",
     *         400="Returned when there is no id in request",
     *     }
     * )
     *
     * @Route(name="api.blog_post.create", path="/blog/posts")
     * @Method("POST")
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="title", nullable=false, strict=true, description="Post title")
     * @RequestParam(name="content", nullable=false, strict=true, description="Post content")
     * @RequestParam(name="tags", nullable=true, strict=true, description="Post tags")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function postSinglePostAction(ParamFetcher $paramFetcher)
    {
        $blogPost = new BlogPost();
        $blogPost->setTitle($paramFetcher->get('title'));
        $blogPost->setContent($paramFetcher->get('content'));
        $blogPost->setTags($paramFetcher->get('tags'));

        $errors = $this->get('validator')->validate($blogPost);
        $view = View::create();
        if (!count($errors)) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($blogPost);
            $manager->flush();
            $view->setData($blogPost)->setStatusCode(RESPONSE::HTTP_CREATED);
        } else {
            $view->setData($errors)->setStatusCode(RESPONSE::HTTP_BAD_REQUEST);
        }

        return $view;
    }

    /**
     * Updates Post with new data, does not create new record.
     * @ApiDoc(
     *     section="Simple Blog",
     *     statusCodes={
     *         200="Returned when successful",
     *         400="Returned when there is no id in request",
     *         404={
     *           "Returned when post was not found",
     *         }
     *     }
     * )
     *
     * @Route(name="api.blog_post.update", path="/blog/posts")
     * @Method("PUT")
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @RequestParam(name="id", nullable=false, strict=true, description="Post ID")
     * @RequestParam(name="title", nullable=false, strict=true, description="Post title")
     * @RequestParam(name="content", nullable=false, strict=true, description="Post content")
     * @RequestParam(name="tags", nullable=true, strict=true, description="Post tags")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function putSinglePostAction(ParamFetcher $paramFetcher)
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:BlogPost');
        /** @var BlogPost $blogPost */
        $blogPost = $repo->find($paramFetcher->get('id'));
        if (!$blogPost) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, "Wrong parameters, please provide value for ID parameter");
        }
        if ($paramFetcher->get('title')) {
            $blogPost->setTitle($paramFetcher->get('title'));
        }
        if ($paramFetcher->get('content')) {
            $blogPost->setContent($paramFetcher->get('content'));
        }
        if ($paramFetcher->get('tags')) {
            $blogPost->setTags($paramFetcher->get('tags'));
        }

        $errors = $this->get('validator')->validate($blogPost);
        $view = View::create();
        if (!count($errors)) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($blogPost);
            $manager->flush();

            $view->setData($blogPost)->setStatusCode(RESPONSE::HTTP_CREATED);
        } else {
            $view->setData($errors)->setStatusCode(RESPONSE::HTTP_BAD_REQUEST);
        }

        return $view;
    }

    /**
     * Updates Post with new data, does not create new record.
     * @ApiDoc(
     *     section="Simple Blog",
     *     statusCodes={
     *         200="Returned when successful",
     *         400="Returned when there is no id in request",
     *         404={
     *           "Returned when post was not found",
     *         }
     *     }
     * )
     *
     * @Route(name="api.blog_post.update_partial", path="/blog/posts")
     * @Method("PATCH")
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     *
     * @RequestParam(name="id", nullable=false, strict=true, description="Post ID")
     * @RequestParam(name="title", nullable=false, strict=true, description="Post title")
     * @RequestParam(name="content", nullable=false, strict=true, description="Post content")
     * @RequestParam(name="tags", nullable=true, strict=true, description="Post tags")
     * @return \FOS\RestBundle\View\View
     */
    public function patchSinglePostAction(ParamFetcher $paramFetcher)
    {

        $blogPost = new BlogPost();
        $blogPost->setTitle($paramFetcher->get('title'));
        $blogPost->setContent($paramFetcher->get('content'));
        $blogPost->setTags($paramFetcher->get('tags'));

        $errors = $this->get('validator')->validate($blogPost);
        $view = View::create();
        if (!count($errors)) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($blogPost);
            $manager->flush();
            $view->setData($blogPost)->setStatusCode(RESPONSE::HTTP_CREATED);
        } else {
            $view->setData($errors)->setStatusCode(RESPONSE::HTTP_BAD_REQUEST);
        }

        return $view;
    }

    /**
     * @ApiDoc(
     *     section="Simple Blog",
     *     description="Return single post from posts list"
     * )
     *
     * @Route(name="api.blog_post.delete", path="/blog/posts")
     * @Method("DELETE")
     *
     * @param ParamFetcher $paramFetcher Paramfetcher
     * @RequestParam(name="id", nullable=false, strict=true, description="Post ID")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function deletePostAction(ParamFetcher $paramFetcher)
    {
        $repo = $this->getDoctrine()->getRepository('AppBundle:BlogPost');
        $manager = $this->getDoctrine()->getManager();

        $blogPost = $repo->find($paramFetcher->get('id'));
        if (!$blogPost) {
            return $this->view(sprintf('Post with id=%d not found, maybe already deleted?', $paramFetcher->get('id')))->setStatusCode(Response::HTTP_NOT_FOUND);
        } else {
            $manager->remove($blogPost);
            $manager->flush();
            return $this->view('Successfully deleted post.')->setStatusCode(Response::HTTP_OK);
        }

        return $this->view('Bad request, please add id parameter to point certain post.')->setStatusCode(Response::HTTP_BAD_REQUEST);
    }

}
