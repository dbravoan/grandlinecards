---
description: Run the Ralph Loop autonomous agent process
---

1. **Read Context**: Read `PRD.md` and `progress.txt` to determine the next action.
2. **Select Task**: Identify the next incomplete task from `PRD.md` (e.g., "Task X").
3. **Execute**: Perform the necessary code changes, file creations, or edits to complete *only* that specific task.
4. **Update Progress**: Append a line to `progress.txt` with the format: `[YYYY-MM-DD HH:MM] Completed: Task X - Description`.
5. **Verify**: Ensure the task is completed correctly (run tests or checks).
6. **Repeat**: If more tasks remain, the loop continues (or stop if running single iteration).
