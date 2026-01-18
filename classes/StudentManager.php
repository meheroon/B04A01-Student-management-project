<?php


class StudentManager
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;

        
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([], JSON_PRETTY_PRINT));
        }
    }

    
    private function read(): array
    {
        $content = file_get_contents($this->filePath);
        if ($content === false || trim($content) === '') {
            return [];
        }

        $data = json_decode($content, true);

        
        return is_array($data) ? $data : [];
    }

   
    private function write(array $students): void
    {
        file_put_contents(
            $this->filePath,
            json_encode(array_values($students), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    public function getAllStudents(): array
    {
        return $this->read();
    }

    public function getStudentById($id): ?array
    {
        $students = $this->read();
        foreach ($students as $student) {
            if ((string)$student['id'] === (string)$id) {
                return $student;
            }
        }
        return null;
    }

  
    private function generateNextId(array $students): int
    {
        $max = 0;
        foreach ($students as $s) {
            $sid = isset($s['id']) ? (int)$s['id'] : 0;
            if ($sid > $max) $max = $sid;
        }
        return $max + 1;
    }


    private function validate(array $data, array $students, ?string $updatingId = null): array
    {
        $name   = trim($data['name'] ?? '');
        $email  = trim($data['email'] ?? '');
        $phone  = trim($data['phone'] ?? '');
        $status = trim($data['status'] ?? '');

        if ($name === '') {
            return [false, "Name is required."];
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [false, "Email must be a valid email address."];
        }

        if ($phone === '') {
            return [false, "Phone is required."];
        }

        if (!preg_match('/^[0-9]+$/', $phone)) {
            return [false, "Phone must contain only numbers."];
        }

        $allowed = ["Active", "On Leave", "Graduated", "Inactive"];
        if (!in_array($status, $allowed, true)) {
            return [false, "Status must be one of: " . implode(", ", $allowed)];
        }

        // Optional: prevent duplicate email (not required, but good practice)
        foreach ($students as $s) {
            if ($updatingId !== null && (string)$s['id'] === (string)$updatingId) {
                continue;
            }
            if (isset($s['email']) && strtolower($s['email']) === strtolower($email)) {
                // You can remove this block if your teacher didn't ask for it.
                return [false, "This email already exists."];
            }
        }

        return [true, ""];
    }

    public function create($data): array
    {
        $students = $this->read();

       
        $newId = $this->generateNextId($students);

       
        foreach ($students as $s) {
            if ((string)$s['id'] === (string)$newId) {
                return [false, "Duplicate ID detected. Try again."];
            }
        }

       
        [$ok, $msg] = $this->validate($data, $students, null);
        if (!$ok) return [false, $msg];

        $student = [
            "id"     => $newId,
            "name"   => trim($data['name']),
            "email"  => trim($data['email']),
            "phone"  => trim($data['phone']),
            "status" => trim($data['status']),
        ];

        $students[] = $student;
        $this->write($students);

        return [true, "Student created successfully."];
    }

    public function update($id, $data): array
    {
        $students = $this->read();
        $found = false;

   
        [$ok, $msg] = $this->validate($data, $students, (string)$id);
        if (!$ok) return [false, $msg];

        foreach ($students as &$student) {
            if ((string)$student['id'] === (string)$id) {
                $student['name']   = trim($data['name']);
                $student['email']  = trim($data['email']);
                $student['phone']  = trim($data['phone']);
                $student['status'] = trim($data['status']);
                $found = true;
                break;
            }
        }
        unset($student);

        if (!$found) {
            return [false, "Student not found."];
        }

        $this->write($students);
        return [true, "Student updated successfully."];
    }

    public function delete($id): array
    {
        $students = $this->read();
        $before = count($students);

        $students = array_filter($students, function ($s) use ($id) {
            return (string)$s['id'] !== (string)$id;
        });

        if (count($students) === $before) {
            return [false, "Student not found."];
        }

        $this->write($students);
        return [true, "Student deleted successfully."];
    }
}
