<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected IncomingRequest|CLIRequest $request;

    protected array $helpers = ['url', 'form', 'text', 'date', 'number'];

    protected ?array $currentUser  = null;
    protected ?array $currentTenant = null;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->loadCurrentUser();
    }

    protected function loadCurrentUser(): void
    {
        $session = session();
        if ($session->get('user_id')) {
            $this->currentUser   = [
                'id'        => $session->get('user_id'),
                'name'      => $session->get('user_name'),
                'email'     => $session->get('user_email'),
                'role'      => $session->get('user_role'),
                'tenant_id' => $session->get('tenant_id'),
                'avatar'    => $session->get('user_avatar'),
            ];
            if ($session->get('tenant_id')) {
                $tenantModel = new \App\Models\TenantModel();
                $this->currentTenant = $tenantModel->find($session->get('tenant_id'));
            }
        }
    }

    protected function setSession(array $user, ?array $tenant = null): void
    {
        session()->set([
            'user_id'    => $user['id'],
            'user_name'  => $user['name'],
            'user_email' => $user['email'],
            'user_role'  => $user['role'],
            'user_avatar' => $user['avatar'] ?? null,
            'tenant_id'  => $user['tenant_id'] ?? null,
        ]);
    }

    protected function logActivity(string $type, string $description, ?string $subjectType = null, ?int $subjectId = null): void
    {
        try {
            $db = db_connect();
            $db->table('activity_logs')->insert([
                'tenant_id'    => $this->currentTenant['id'] ?? null,
                'user_id'      => $this->currentUser['id'] ?? null,
                'type'         => $type,
                'description'  => $description,
                'subject_type' => $subjectType,
                'subject_id'   => $subjectId,
                'ip_address'   => $this->request->getIPAddress(),
                'user_agent'   => $this->request->getUserAgent()->getAgentString(),
                'created_at'   => date('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            // non-critical, swallow
        }
    }

    protected function jsonResponse(mixed $data, int $status = 200): ResponseInterface
    {
        return $this->response
            ->setStatusCode($status)
            ->setJSON($data);
    }
}
