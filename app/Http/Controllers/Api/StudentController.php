<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentListResource;
use App\Http\Resources\StudentResource;
use App\Models\Api\Student;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $perPage = request('per_page', 10);
        $search = request('search', '');
        $sortField = request('sort_field', 'created_at');
        $sortDirection = request('sort_direction', 'desc');

        $query = Student::query()
            ->where('first_name', 'like', "%{$search}%")
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);

        return StudentListResource::collection($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StudentRequest $request
     *
     * @return Response|StudentResource
     *
     * @throws Exception
     */
    public function store(StudentRequest $request): Response|StudentResource
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        /** @var UploadedFile $image */
        $image = $data['image'] ?? null;
        // Check if image was given and save on local file system
        if ($image) {
            $relativePath = $this->saveImage($image);
            $data['image'] = URL::to(Storage::url($relativePath));
            $data['image_mime'] = $image->getClientMimeType();
            $data['image_size'] = $image->getSize();
        }

        $Student = Student::create($data);

        return new StudentResource($Student);
    }

    /**
     * Display the specified resource.
     *
     * @param Student $Student
     * @return Response|StudentResource
     */
    public function show(Student $Student): Response|StudentResource
    {
        return new StudentResource($Student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StudentRequest $request
     * @param Student $Student
     *
     * @return Response|StudentResource
     *
     * @throws Exception
     */
    public function update(StudentRequest $request, Student $Student): Response|StudentResource
    {
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;

        /** @var UploadedFile $image */
        $image = $data['image'] ?? null;
        // Check if image was given and save on local file system
        if ($image) {
            $relativePath = $this->saveImage($image);
            $data['image'] = URL::to(Storage::url($relativePath));
            $data['image_mime'] = $image->getClientMimeType();
            $data['image_size'] = $image->getSize();

            // If there is an old image, delete it
            if ($Student->image) {
                Storage::deleteDirectory('/public/' . dirname($Student->image));
            }
        }

        $Student->update($data);

        return new StudentResource($Student);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Student $Student
     * @return Response
     */
    public function destroy(Student $Student): Response
    {
        $Student->delete();

        return response()->noContent();
    }

    /**
     * @param UploadedFile $image
     *
     * @return string
     *
     * @throws Exception
     */
    private function saveImage(UploadedFile $image): string
    {
        $path = 'images/' . Str::random();
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path, 0755, true);
        }
        if (!Storage::putFileAS('public/' . $path, $image, $image->getClientOriginalName())) {
            throw new Exception("Unable to save file \"{$image->getClientOriginalName()}\"");
        }

        return $path . '/' . $image->getClientOriginalName();
    }
}