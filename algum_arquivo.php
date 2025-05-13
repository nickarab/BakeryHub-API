// ...existing code...
public function destroy(string $id)
{
    try {
        $this->userService->destroyUser($id);

        return response()->json([
            'message' => 'user has been deleted sucessfully'
        ], Response::HTTP_OK);
    } catch (Exception $e) {
        return response()->json([
            'error' => 'Failed to delete user',
            'details' => $e->getMessage()
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
// ...existing code...
