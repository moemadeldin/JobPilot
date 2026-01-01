<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Key and Organization
    |--------------------------------------------------------------------------
    |
    | Here you may specify your OpenAI API Key and organization. This will be
    | used to authenticate with the OpenAI API - you can find your API key
    | and organization on your OpenAI dashboard, at https://openai.com.
    */

    'api_key' => env('OPENAI_API_KEY'),
    'organization' => env('OPENAI_ORGANIZATION'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Project
    |--------------------------------------------------------------------------
    |
    | Here you may specify your OpenAI API project. This is used optionally in
    | situations where you are using a legacy user API key and need association
    | with a project. This is not required for the newer API keys.
    */
    'project' => env('OPENAI_PROJECT'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI Base URL
    |--------------------------------------------------------------------------
    |
    | Here you may specify your OpenAI API base URL used to make requests. This
    | is needed if using a custom API endpoint. Defaults to: api.openai.com/v1
    */
    'base_uri' => env('OPENAI_BASE_URL'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout may be used to specify the maximum number of seconds to wait
    | for a response. By default, the client will time out after 30 seconds.
    */

    'request_timeout' => env('OPENAI_REQUEST_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | AI Prompts
    |--------------------------------------------------------------------------
    |
    | Here you may specify the prompts used for AI services like evaluation
    | and mock interview generation. These are configurable for easy updates.
    */

    'prompts' => [
        'evaluation' => <<<'PROMPT'
You are an AI HR assistant evaluating how well a candidate's resume fits a job description.

Compare the following RESUME and JOB DESCRIPTION, then respond **strictly in valid JSON**:
{
  "score": number (integer 0–100),
  "feedback": {
    "strengths": string[],
    "weaknesses": string[]
  },
  "suggestions": string
}

RESUME:
{resume}

JOB DESCRIPTION:
{job_description}
PROMPT,

        'mock_interview' => <<<'PROMPT'
You are an AI HR assistant generating mock interview Q&A for a candidate based on their resume and the job description.

Generate 10-15 highly relevant question-answer pairs commonly asked in interviews for this position. Keep answers concise yet informative.

Respond strictly in valid JSON:
{
  "qa": [
    {"question": "Question 1?", "answer": "Answer 1."},
    {"question": "Question 2?", "answer": "Answer 2."},
    ...
  ]
}

RESUME:
{resume}

JOB DESCRIPTION:
{job_description}
PROMPT,
    ],
];
