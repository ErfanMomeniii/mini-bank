<?php

declare(strict_types=1);

namespace ErfanMomeniii\MiniBank\Controller;

use ErfanMomeniii\MiniBank\Model\User;
use ErfanMomeniii\MiniBank\Service\UserService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

readonly class UserController
{
    public function __construct(private UserService $userService)
    {
    }

    public function index(Request $request, Response $response): Response
    {
        $users = array_map(fn(User $u) => $this->serialize($u), $this->userService->findAll());
        return $this->json($response, $users);
    }

    public function create(Request $request, Response $response): Response
    {
        $body = $this->body($request);
        $user = $this->userService->create(
            $body['phone_number'] ?? '',
            $body['status'] ?? 'Active',
        );
        return $this->json($response, $this->serialize($user), 201);
    }

    public function show(Request $request, Response $response, array $args): Response
    {
        $user = $this->userService->findById((int) $args['id']);
        return $this->json($response, $this->serialize($user));
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $body = $this->body($request);
        $user = $this->userService->update(
            (int) $args['id'],
            $body['phone_number'] ?? '',
            $body['status'] ?? 'Active',
        );
        return $this->json($response, $this->serialize($user));
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        $this->userService->delete((int) $args['id']);
        return $response->withStatus(204);
    }

    private function serialize(User $user): array
    {
        return [
            'id'           => $user->getId(),
            'phone_number' => $user->getPhoneNumber(),
            'status'       => $user->getStatus()->value,
        ];
    }

    private function json(Response $response, mixed $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

    private function body(Request $request): array
    {
        return (array) json_decode((string) $request->getBody(), true);
    }
}
