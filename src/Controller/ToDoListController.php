<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ToDoListController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Task::class);
        $tasks=$repository->findBy([],['id'=>'DESC']);
        return $this->render('ToDo/index.html.twig', [
            "tasks"=>$tasks,

        ]);
    }

    /**
     * @Route("/add", name="add_task",methods={"POST"})
     */
    public function create(Request $request)
    {   
       
        $em = $this->getDoctrine()->getManager();
        $title = trim($request->request->get('title'));
        $task=new Task();
        $task->setTitle($title);
        $task->setSatus(false);
        try {
            $em->persist($task);
            $em->flush();
        } catch (\Throwable $th) {
            throw $th;
        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/switch/{id}", name="switch_status")
     */
    public function switch($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Task::class);
        $task=$repository->find($id);
        //var_dump($task);exit($id);
        if ($task) {
            $task->setSatus(!$task->getSatus());
            $em->persist($task);
            $em->flush();
        }
        return $this->redirectToRoute('home');
    }
      /**
     * @Route("/delete/{id}", name="delete_task")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Task::class);
        $task=$repository->find($id);
        //var_dump($task);exit($id);
        if ($task) {
            $em->persist($task);
            $em->remove($task);
            $em->flush();
        }
        return $this->redirectToRoute('home');
    }
   
}
