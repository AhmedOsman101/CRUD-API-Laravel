# alternative way to get the params of a method of a class

    /**
     * Generate a response array with message, state, status code, and data.
     * This method uses reflection to map the provided method arguments
     * to the expected parameter names and builds a response array.
     *
     * @param string $message The message to include in the response
     * @param string $state The state to include in the response
     * @param int $statusCode The status code to include in the response
     * @param mixed $data Additional data to include in the response
     * @return array The generated response array
     */
    public function generateResponse(
        string $message = null,
        string $state = null,
        int $statusCode = null,
        mixed $data = null
    ): array {

        // Get an array of the arguments passed to the function
        $args = func_get_args();

        // Create a new ReflectionMethod object for the current method
        $reflection = new ReflectionMethod(__CLASS__, __FUNCTION__);

        // Get an array of the parameters of the method
        $params = $reflection->getParameters();

        // Loop over each parameter
        foreach ($params as $key => $param) {
            /* If the parameter is not empty, add it to the response array
            The key in the response array is the name of the parameter */
            if (!empty($args[$key])) {
                $response[$param->getName()] = $args[$key];
            }
        }

        // Return the response array, or null if the response array is empty
        return $response ?? null;
    }
