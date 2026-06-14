<?php
namespace App\Controllers;
use Core\Api\ApiController;
use App\Repositories\UserRepository;
use App\Http\Resources\UserResource;
final class UserApiController extends ApiController{protected string $repository=UserRepository::class; protected string $resource=UserResource::class;}
