<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | AI Prompts
    |--------------------------------------------------------------------------
    |
    | Here you may specify the prompts used for AI services like evaluation
    | and mock interview generation. These are configurable for easy updates.
    */

    'evaluation' => <<<'PROMPT'
You are an AI HR assistant evaluating how well a candidate's resume fits a job description.

Compare the following RESUME and JOB DESCRIPTION, then respond **strictly in valid JSON**:
{
  "score": number (integer 0-100),
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
];
