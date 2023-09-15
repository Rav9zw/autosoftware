<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Messages;


class ApiController extends AbstractController
{
    private Messages $messages;

    public function __construct(Messages $messages)
    {
        $this->messages = $messages;
    }


    #[Route('/api/message/', name: 'create_message', methods: 'POST')]
    public function createMessage(Request $request): Response
    {
        $content = json_decode($request->getContent(), true);
        return $this->json($this->messages->createNew($content));
    }


    #[Route('/api/message/{uuid}', name: 'get_message', methods: 'GET')]
    public function getMessage($uuid): Response
    {
        return $this->json($this->messages->findByUuid($uuid));
    }

    #[Route('/api/message_list/', name: 'list_message', methods: 'GET')]
    public function listMessage(Request $request): Response
    {
        $sortBy = $request->query->get('sort_by', 'name');
        $orderBy = $request->query->get('order_by', 'asc');

        $validate = $this->messages->validateRequestForList($sortBy, $orderBy);
        if ($validate) {
            return $this->json($validate);
        }
        $list = $this->messages->list($sortBy, $orderBy);


        return $this->json($list);
    }


}
