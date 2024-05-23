<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

class UserService extends BaseService
{
    protected $user;
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
    }

    /**
     * @param $data
     * @return array|array[]
     */
    public function login($data): array
    {
        try {
            $user = $this->userRepository->getByEmail($data['email']);

            // Khi không tìm thấy user với email
            if (!$user) {
                return [
                    'status'  => $this->httpStatusCode['404'],
                    'message' => $this->message['USER']['EMAIL_NOT_FOUND']
                ];
            }

            // Khi mật khẩu không đúng
            if (!Hash::check($data['password'], $user['password'])) {
                return [
                    'success' => false,
                    'message' => $this->message['USER']['VALID_PASSWORD']
                ];
            }

            // Khi user chưa được active
            if ($user['status'] != 'active') {
                return [
                    'success' => false,
                    'message' => $this->message['USER']['INACTIVE']
                ];
            }

            $tokenResult = $user->createToken('Personal Access Token');
            $tokenResult->token->save();

            return [
                'data' => [
                    'user'  => $user,
                    'token' => $tokenResult->accessToken
                ]
            ];

        }
        catch (\Exception|\Throwable $e) {
            Log::error($e);
            return [
                'status'  => $this->httpStatusCode['500'],
                'message' => $this->httpStatusText['500']
            ];
        }
    }

    /**
     * @return array|array[]
     */
    public function logout(): array
    {
        try {
            $tokens = Auth::user()->tokens->pluck('id');

            Token::whereIn('id', $tokens)->update(['revoked' => true]);

            RefreshToken::whereIn('access_token_id', $tokens)->update(['revoked' => true]);

            return [
                'message' => $this->message['USER']['LOGOUT_SUCCESS']
            ];

        }
        catch (\Exception|\Throwable $e) {
            Log::error($e);
            return array(
                'status'  => $this->httpStatusCode['500'],
                'message' => $this->httpStatusText['500']
            );
        }
    }

    /**
     * @param $userId
     * @param $status
     * @return array|string[]
     */
    public function updateStatus($userId, $status): array
    {
        try {
            $userAuth = $this->userRepository->getDetail(Auth::id());

            // Khi vai trò không phải admin
            if ($userAuth['role'] != 'admin') {
                Log::error('Not Permission');
                return [
                    'status'  => $this->httpStatusCode['403'],
                    'message' => $this->httpStatusText['403']
                ];
            }

            $user = $this->userRepository->find($userId);

            // Khi không tìm thấy user
            if (!$user) {
                Log::error($this->message['USER']['NOT_FOUND']);
                return [
                    'status'  => $this->httpStatusCode['404'],
                    'message' => $this->message['USER']['NOT_FOUND']
                ];
            }

            // Khi trạng thái không thay đổi
            if ($user['status'] == $status) {
                Log::error($this->message['STATUS_UPDATED_ERROR']);
                return [
                    'success' => false,
                    'message' => $this->message['STATUS_UPDATED_ERROR']
                ];
            }

            DB::beginTransaction();

            $updated = $this->userRepository->updateStatus($userId, $status);

            // Khi cập nhật không thành công
            if (!$updated) {
                Log::error($this->message['UPDATE_FAILED']);
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => $this->message['UPDATE_FAILED']
                ];
            }

            DB::commit();

            return [
                'message' => $this->message['UPDATED']
            ];

        }
        catch (\Exception|\Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return array(
                'status'  => $this->httpStatusCode['500'],
                'message' => $this->httpStatusText['500']
            );
        }
    }
}
